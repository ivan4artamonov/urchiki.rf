<?php

namespace App\Livewire\Admin\Worksheets;

use App\Models\Grade;
use App\Models\Quarter;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Collection;

trait HasWorksheetFormOptions
{
	public function updatedFormSubjectId(): void
	{
		$this->form->topicId = null;
	}

	public function updatedFormGradeId(): void
	{
		$this->form->quarterId = null;
	}

	/**
	 * Возвращает данные для зависимых списков формы рабочего листа.
	 *
	 * @return array<string, Collection<int, mixed>>
	 */
	protected function worksheetFormOptions(): array
	{
		$subjectId = isset($this->form->subjectId) ? (string) $this->form->subjectId : '';
		$gradeId = isset($this->form->gradeId) ? (string) $this->form->gradeId : '';

		$subjects = Subject::query()
			->ordered()
			->get(['id', 'name']);

		$topics = Topic::query()
			->when($subjectId !== '', fn ($query) => $query->where('subject_id', (int) $subjectId))
			->ordered()
			->get(['id', 'name']);

		$grades = Grade::query()
			->ordered()
			->get(['id', 'number']);

		$quarters = Quarter::query()
			->when($gradeId !== '', fn ($query) => $query->where('grade_id', (int) $gradeId))
			->ordered()
			->get(['id', 'number']);

		return [
			'subjects' => $subjects,
			'topics' => $topics,
			'grades' => $grades,
			'quarters' => $quarters,
		];
	}
}
