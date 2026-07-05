<?php

namespace Webkul\Shop\Mail\Customer;

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

        $template = app(EmailTemplateRepository::class)->findActiveByCode('shop.customers.forgot-password');

        $mail = (new MailMessage)
            ->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->subject($template && $template->subject ? $template->subject : trans('shop::app.emails.customers.forgot-password.subject'));

        if ($template) {
            $body = strtr($template->content, [
                '{{customer_name}}' => $notifiable->name,
                '{{reset_password_url}}' => route('shop.customers.reset_password.create', $this->token),
            ]);

            return $mail->view('shop::emails.layout', ['slot' => new HtmlString($body)]);
        }

        return $mail->view('shop::emails.customers.forgot-password', [
            'userName' => $notifiable->name,
            'token' => $this->token,
        ]);
    }
}
