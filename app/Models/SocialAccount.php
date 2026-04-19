<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Привязка учётной записи пользователя к аккаунту OAuth-провайдера.
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider Имя драйвера Socialite (vkontakte, yandex, mailru).
 * @property string $provider_user_id Идентификатор пользователя у провайдера.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class SocialAccount extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
    ];

    /**
     * @return BelongsTo<User, SocialAccount>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
