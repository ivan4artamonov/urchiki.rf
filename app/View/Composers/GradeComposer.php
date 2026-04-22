<?php

namespace App\View\Composers;

use App\Models\Grade;
use Illuminate\Contracts\View\View;

/**
 * Подставляет в футер сайта список школьных параллелей из БД (по возрастанию номера).
 */
final class GradeComposer
{
	public function compose(View $view): void
	{
		$view->with(
			'footerGrades',
			Grade::query()->get(),
		);
	}
}
