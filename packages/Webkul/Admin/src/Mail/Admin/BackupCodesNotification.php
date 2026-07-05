<?php

namespace Webkul\Admin\Mail\Admin;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\User\Contracts\Admin;

class BackupCodesNotification extends Mailable
{
    /**
     * Create a new mailable instance.
     *
     * @param  array  $backupCodes  The plain backup codes (only the hashed copies are stored).
     */
    public function __construct(public Admin $admin, public array $backupCodes = []) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->admin->email),
            ],
            subject: $this->resolveSubject('admin.backup-codes', 'admin::app.account.emails.backup-codes.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $codesList = implode('', array_map(
            fn ($code) => '<div style="background: #F8F9FA;border: 2px solid #060C3B;border-radius: 4px;padding: 12px;text-align: center;font-family: monospace;font-size: 16px;font-weight: bold;color: #060C3B;">'.e($code).'</div>',
            $this->backupCodes
        ));

        return $this->resolveContent('admin.backup-codes', 'admin::emails.admin.backup-codes', [
            '{{admin_name}}' => $this->admin->name,
            '{{backup_codes}}' => '<div style="display: grid;grid-template-columns: repeat(2, 1fr);gap: 12px;margin-bottom: 24px;">'.$codesList.'</div>',
        ], fallbackWith: ['backupCodes' => $this->backupCodes]);
    }
}
