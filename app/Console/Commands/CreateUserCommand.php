<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Создаёт пользователя через интерактивный CLI-опрос.
 */
class CreateUserCommand extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Create a new user with optional name, password and admin flag';

    /**
     * Выполняет команду.
     */
    public function handle(): int
    {
        $email = $this->askForUniqueEmail();
        $name = trim((string) $this->ask('Имя (по умолчанию пустое)'));
        $password = $this->secret('Пароль (по умолчанию пустой)');
        $isAdmin = $this->confirm('Сделать пользователя администратором?', false);

        $user = User::query()->create([
            'name' => filled($name) ? $name : null,
            'email' => $email,
            'password' => filled($password) ? $password : null,
            'is_admin' => $isAdmin,
        ]);

        $this->info("Пользователь #{$user->id} ({$user->email}) успешно создан.");

        return self::SUCCESS;
    }

    /**
     * Запрашивает email до тех пор, пока он не будет валидным и уникальным.
     */
    private function askForUniqueEmail(): string
    {
        while (true) {
            $input = trim((string) $this->ask('Email'));
            $email = User::normalizeEmail($input);

            if ($email === '') {
                $this->error('Email обязателен.');

                continue;
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Некорректный формат email.');

                continue;
            }

            if (User::query()->whereEmailNormalized($email)->exists()) {
                $this->error('Пользователь с таким email уже существует.');

                continue;
            }

            return $email;
        }
    }
}
