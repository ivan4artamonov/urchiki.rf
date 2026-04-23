<?php

namespace App\Livewire\Admin\Worksheets;

use App\Models\Grade;
use App\Models\Quarter;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Worksheet;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
	use WithPagination;

	#[Url(as: 'subject')]
	public string $subjectId = '';

	#[Url(as: 'topic')]
	public string $topicId = '';

	#[Url(as: 'grade')]
	public string $gradeId = '';

	#[Url(as: 'quarter')]
	public string $quarterId = '';

	#[Url(as: 'title')]
	public string $titleSearch = '';

	public function updatedSubjectId(): void
	{
		$this->topicId = '';
	}

	public function updatedGradeId(): void
	{
		$this->quarterId = '';
	}

	public function updated(string $property): void
	{
		if (in_array($property, ['subjectId', 'topicId', 'gradeId', 'quarterId', 'titleSearch'], true)) {
			$this->resetPage();
		}
	}

	public function render()
	{
		$worksheets = Worksheet::query()
			->with(['topic.subject', 'quarter.grade'])
			->when($this->subjectId !== '', fn ($query) => $query->forSubject((int) $this->subjectId))
			->when($this->topicId !== '', fn ($query) => $query->forTopic((int) $this->topicId))
			->when($this->gradeId !== '', fn ($query) => $query->forGrade((int) $this->gradeId))
			->when($this->quarterId !== '', fn ($query) => $query->forQuarter((int) $this->quarterId))
			->when($this->titleSearch !== '', fn ($query) => $query->where('title', 'like', '%' . trim($this->titleSearch) . '%'))
			->latestUploaded()
			->paginate(30);

		$subjects = Subject::query()
			->ordered()
			->get(['id', 'name']);

		$topics = Topic::query()
			->with('subject:id,name')
			->when($this->subjectId !== '', fn ($query) => $query->where('subject_id', (int) $this->subjectId))
			->ordered()
			->get(['id', 'subject_id', 'name']);

		$grades = Grade::query()
			->ordered()
			->get(['id', 'number']);

		$quarters = Quarter::query()
			->with('grade:id,number')
			->when($this->gradeId !== '', fn ($query) => $query->where('grade_id', (int) $this->gradeId))
			->ordered()
			->get(['id', 'grade_id', 'number']);

		return view('livewire.admin.worksheets.index', [
			'worksheets' => $worksheets,
			'subjects' => $subjects,
			'topics' => $topics,
			'grades' => $grades,
			'quarters' => $quarters,
		])
			->layout('admin', ['adminSectionTitle' => 'рабочие листы']);
	}
}
