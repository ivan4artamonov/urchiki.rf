@include('livewire.admin.topics._page', [
	'title' => 'Редактирование темы: ' . $form->name,
	'submitAction' => 'updateTopic',
	'submitLabel' => 'Сохранить изменения',
])

