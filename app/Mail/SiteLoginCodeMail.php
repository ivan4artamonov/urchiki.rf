<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Письмо с одноразовым кодом для входа или регистрации на сайте.
 */
class SiteLoginCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Создаёт письмо с кодом подтверждения.
     *
     * @param  string  $code  Четырёхзначный код
     */
    public function __construct(
        public string $code,
    ) {}

    /**
     * Возвращает конверт сообщения.
     *
     * @return Envelope Тема и метаданные письма
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Код для входа — '.config('app.name'),
        );
    }

    /**
     * Возвращает содержимое письма.
     *
     * @return Content HTML-шаблон с кодом
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.site-login-code',
        );
    }

    /**
     * Возвращает вложения (нет).
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
