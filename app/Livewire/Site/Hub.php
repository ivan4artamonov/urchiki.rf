<?php

namespace App\Livewire\Site;

use App\Models\Grade as GradeModel;
use App\Models\Subject as SubjectModel;
use App\Models\Topic as TopicModel;
use App\Support\SiteHubUrl;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Публичный хаб каталога рабочих листов по вложенному URL.
 *
 * Первый сегмент пути может быть слагом предмета или слагом класса (параллели).
 * При совпадении слага в обеих таблицах выбирается предмет, чтобы не ломать существующие URL.
 *
 * Режимы:
 * — предмет → список классов;
 * — предмет + класс → список тем;
 * — предмет + класс + тема → конечная страница темы;
 * — только класс (СЕО) → список предметов со ссылками вида «предмет/класс».
 */
class Hub extends Component
{
	/**
	 * Текущий предмет из URL (если первый сегмент — предмет или задан в паре «предмет/класс»).
	 *
	 * @var SubjectModel|null
	 */
	public ?SubjectModel $subject = null;

	/**
	 * Текущий класс из URL (опционально или как единственный первый сегмент).
	 *
	 * @var GradeModel|null
	 */
	public ?GradeModel $grade = null;

	/**
	 * Текущая тема из URL (опционально).
	 *
	 * @var TopicModel|null
	 */
	public ?TopicModel $topic = null;

	/**
	 * Разбирает URL и заполняет свойства хаба.
	 *
	 * @param string $slug1 Первый сегмент: предмет или класс.
	 * @param string|null $slug2 Второй сегмент при разборе «предмет → класс → тема».
	 * @param string|null $slug3 Третий сегмент — слаг темы.
	 * @return void
	 * @throws NotFoundHttpException Если сегменты не соответствуют ни одному допустимому варианту URL.
	 */
	public function mount(string $slug1, ?string $slug2 = null, ?string $slug3 = null): void
	{
		// Ищем обе сущности по одному и тому же первому сегменту; дальше решаем ветку с приоритетом предмета.
		$subjectBySlug = SubjectModel::query()->where('slug', $slug1)->first();
		$gradeBySlug = GradeModel::query()->where('slug', $slug1)->first();

		if ($subjectBySlug !== null) {
			// Ветка «сначала предмет»: slug2/slug3 при необходимости дополняют класс и тему.
			$this->subject = $subjectBySlug;

			if ($slug2 !== null) {
				// Второй сегмент в этой ветке всегда трактуется как класс (параллель).
				$this->grade = GradeModel::query()->where('slug', $slug2)->first();
				if (! $this->hasGrade()) {
					abort(404);
				}
			}

			if ($slug3 !== null) {
				// Тема не может быть указана без класса в середине пути.
				if (! $this->hasGrade()) {
					abort(404);
				}
				$this->topic = TopicModel::query()->where('slug', $slug3)->first();
				if (! $this->hasTopic()) {
					abort(404);
				}
				// Тема привязана к предмету в БД — отсекаем чужие слаги.
				if ($this->topic->subject_id !== $this->subject->getKey()) {
					abort(404);
				}
			}

			return;
		}

		if ($gradeBySlug !== null) {
			// Ветка «сначала класс» (СЕО): допускается ровно один сегмент пути.
			if ($slug2 !== null || $slug3 !== null) {
				abort(404);
			}
			$this->grade = $gradeBySlug;

			return;
		}

		abort(404);
	}

	/**
	 * Возвращает универсальную страницу каталога.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return View Представление соответствующего уровня каталога.
	 */
	public function render(): View
	{
		$links = $this->resolveLinks();
		$title = $this->resolveTitle();

		return view('livewire.site.hub', [
			'subject' => $this->subject,
			'grade' => $this->grade,
			'topic' => $this->topic,
			'links' => $links,
			'pageTitle' => $title,
			'breadcrumbs' => $this->resolveBreadcrumbs(),
		])->layout('site', ['title' => $title . ' — ' . config('app.name')]);
	}

	/**
	 * Проверяет, что в хабе задан предмет (первая ветка URL или контекст после выбора).
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return bool True, если свойство {@see $subject} заполнено.
	 */
	protected function hasSubject(): bool
	{
		return $this->subject !== null;
	}

	/**
	 * Проверяет, что в хабе задан класс (параллель).
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return bool True, если свойство {@see $grade} заполнено.
	 */
	protected function hasGrade(): bool
	{
		return $this->grade !== null;
	}

	/**
	 * Проверяет, что в хабе задана тема.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return bool True, если свойство {@see $topic} заполнено.
	 */
	protected function hasTopic(): bool
	{
		return $this->topic !== null;
	}

	/**
	 * Режим СЕО: в URL только параллель, предмет в сегментах не участвует.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return bool True, если открыт хаб «класс первым» без предмета.
	 */
	protected function isGradeFirstSeoOnly(): bool
	{
		return ! $this->hasSubject() && $this->hasGrade();
	}

	/**
	 * Уровень «только предмет»: список параллелей внутри предмета.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return bool True, если задан предмет, но класс ещё не выбран.
	 */
	protected function isSubjectOnlyHub(): bool
	{
		return $this->hasSubject() && ! $this->hasGrade();
	}

	/**
	 * Уровень «предмет + класс» без темы: список тем.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return bool True, если выбраны предмет и класс, тема не указана.
	 */
	protected function isSubjectGradeWithoutTopicHub(): bool
	{
		return $this->hasSubject() && $this->hasGrade() && ! $this->hasTopic();
	}

	/**
	 * Лист темы: полная тройка «предмет — класс — тема».
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return bool True, если заданы все три уровня каталога.
	 */
	protected function isSubjectGradeTopicLeaf(): bool
	{
		return $this->hasSubject() && $this->hasGrade() && $this->hasTopic();
	}

	/**
	 * Формирует заголовок страницы по текущему уровню вложенности.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return string Заголовок для h1 и title страницы.
	 */
	private function resolveTitle(): string
	{
		// Самый глубокий уровень: тема в контексте предмета и параллели.
		if ($this->isSubjectGradeTopicLeaf()) {
			return 'Рабочие листы: ' . $this->subject->name . ', ' . $this->grade->label . ', тема ' . $this->topic->name;
		}

		// Выбраны предмет и класс — дальше пользователь выбирает тему.
		if ($this->isSubjectGradeWithoutTopicHub()) {
			return 'Рабочие листы: ' . $this->subject->name . ', ' . $this->grade->label;
		}

		// Только предмет — обзор параллелей для этого предмета.
		if ($this->isSubjectOnlyHub()) {
			return 'Рабочие листы по предмету ' . $this->subject->name;
		}

		// Только параллель в URL — подсказка выбрать предмет (СЕО-лендинг класса).
		if ($this->isGradeFirstSeoOnly()) {
			return 'Рабочие листы для ' . $this->grade->label . ': выберите предмет';
		}

		// Защитный fallback: после корректного mount сюда не попадаем.
		return 'Рабочие листы — ' . config('app.name');
	}

	/**
	 * Формирует хлебные крошки в зависимости от текущего уровня каталога.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return array<string, string> Список крошек: подпись => ссылка/пустая строка для текущей страницы.
	 */
	private function resolveBreadcrumbs(): array
	{
		// Одна активная крошка — параллель; предмет появится после перехода по ссылке предмет/класс.
		if ($this->isGradeFirstSeoOnly()) {
			return [$this->grade->label => ''];
		}

		// Неконсистентное состояние после mount не ожидается — не рисуем цепочку.
		if (! $this->hasSubject()) {
			return [];
		}

		// Корень ветки «предмет»: текущая страница — сам предмет.
		if ($this->isSubjectOnlyHub()) {
			return [$this->subject->name => ''];
		}

		$breadcrumbs = [];
		// С предмета можно вернуться на список параллелей этого предмета.
		$breadcrumbs[$this->subject->name] = SiteHubUrl::make($this->subject);

		// Параллель выбрана, тема ещё нет — активна только метка класса.
		if ($this->isSubjectGradeWithoutTopicHub()) {
			$breadcrumbs[$this->grade->label] = '';

			return $breadcrumbs;
		}

		// Полная тройка: с «предмет+класс» можно перейти к списку тем; тема — текущая страница.
		$breadcrumbs[$this->grade->label] = SiteHubUrl::make($this->subject, $this->grade);
		$breadcrumbs[$this->topic->name] = '';

		return $breadcrumbs;
	}

	/**
	 * Возвращает ссылки на дочерний уровень каталога.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return Collection<int, array{label: string, href: string}> Ссылки для навигационных кнопок.
	 */
	private function resolveLinks(): Collection
	{
		// На странице темы дочерних узлов каталога нет — остаётся только контент/заглушка во вьюхе.
		if ($this->hasTopic()) {
			return collect();
		}

		// Известны предмет и параллель — предлагаем темы этого предмета.
		if ($this->isSubjectGradeWithoutTopicHub()) {
			return $this->subject
				->topics()
				->get()
				->map(fn (TopicModel $topic): array => [
					'label' => $topic->name,
					'href' => SiteHubUrl::make($this->subject, $this->grade, $topic),
				]);
		}

		// Известен только предмет — шаг вниз к выбору параллели.
		if ($this->isSubjectOnlyHub()) {
			return GradeModel::query()
				->get()
				->map(fn (GradeModel $grade): array => [
					'label' => $grade->label,
					'href' => SiteHubUrl::make($this->subject, $grade),
				]);
		}

		// СЕО-страница параллели — уводим на уточняющий URL «предмет/класс».
		if ($this->isGradeFirstSeoOnly()) {
			return SubjectModel::query()
				->ordered()
				->get()
				->map(fn (SubjectModel $subject): array => [
					'label' => $subject->name,
					'href' => SiteHubUrl::make($subject, $this->grade),
				]);
		}

		return collect();
	}
}
