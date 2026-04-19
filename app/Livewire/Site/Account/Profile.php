<?php

namespace App\Livewire\Site\Account;

use App\Livewire\Site\Forms\ProfileForm;
use App\Support\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Раздел учётной записи: редактирование личных данных (имя и электронная почта).
 */
class Profile extends Component
{
    public ProfileForm $form;

    /**
     * Подставляет данные авторизованного пользователя в форму (маршрут защищён middleware «auth»).
     *
     * @return void
     */
    public function mount(): void
    {
        $this->form->fillFromAuthUser();
    }

    /**
     * Сохраняет изменения профиля и показывает сообщение об успехе.
     *
     * @return void
     */
    public function saveProfile(): void
    {
        $this->form->save();
        Notification::success('Изменения сохранены.');
        $this->redirect(route('site.account.profile'), navigate: false);
    }

    /**
     * Возвращает представление страницы профиля.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.site.account.profile')
            ->layout('site', [
                'title' => 'Профиль — '.config('app.name'),
            ]);
    }
}
