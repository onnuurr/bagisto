<?php

namespace Webkul\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable as BaseMailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;
use Webkul\Core\Repositories\EmailTemplateRepository;

class Mailable extends BaseMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Add the sender to the message.
     *
     * @param  Message  $message
     */
    protected function buildFrom($message): Mailable
    {
        ! empty($this->from)
            ? $message->from($this->from[0]['address'], $this->from[0]['name'])
            : $message->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name']);

        return $this;
    }

    /**
     * Resolve the mail subject, preferring the admin-customized email
     * template subject over the translated fallback.
     *
     * @param  string  $code
     * @param  string  $fallbackKey
     * @return string
     */
    protected function resolveSubject($code, $fallbackKey, array $replace = [])
    {
        $template = app(EmailTemplateRepository::class)->findActiveByCode($code);

        return $template && $template->subject
            ? $template->subject
            : trans($fallbackKey, $replace);
    }

    /**
     * Resolve the mail content, preferring the admin-customized email
     * template (rendered within the shared layout) over the default
     * Blade view.
     *
     * @param  string  $code
     * @param  string  $fallbackView
     * @param  string  $layoutView
     * @return Content
     */
    protected function resolveContent($code, $fallbackView, array $tokens = [], $layoutView = 'shop::emails.layout')
    {
        $template = app(EmailTemplateRepository::class)->findActiveByCode($code);

        if (! $template) {
            return new Content(view: $fallbackView);
        }

        return new Content(
            view: $layoutView,
            with: ['slot' => new HtmlString(strtr($template->content, $tokens))],
        );
    }
}
