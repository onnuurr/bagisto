<?php

namespace Webkul\Admin\Mail\Admin;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use Webkul\Core\Repositories\EmailTemplateRepository;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $template = app(EmailTemplateRepository::class)->findActiveByCode('admin.reset-password');

        $mail = (new MailMessage)
            ->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->subject($template && $template->subject ? $template->subject : trans('admin::app.emails.admin.forgot-password.subject'));

        if ($template) {
            $body = strtr($template->content, [
                '{{admin_name}}' => $notifiable->name,
                '{{reset_password_url}}' => route('admin.reset_password.create', $this->token),
            ]);

            return $mail->view('admin::emails.layout', ['slot' => new HtmlString($body)]);
        }

        return $mail->view('admin::emails.admin.forget-password', [
            'userName' => $notifiable->name,
            'token' => $this->token,
        ]);
    }
}
