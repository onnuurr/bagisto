<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->templates() as $template) {
            DB::table('core_email_templates')->insert(array_merge($template, [
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('core_email_templates')->whereIn('code', array_column($this->templates(), 'code'))->delete();
    }

    /**
     * Default, customizable notification email templates.
     */
    private function templates(): array
    {
        return [
            [
                'code' => 'shop.customers.update-password',
                'name' => 'Password Updated — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">Password Updated!</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">You are receiving this email because you have updated your password.</p>
                HTML,
            ], [
                'code' => 'shop.customers.email-verification',
                'name' => 'Email Verification — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">Welcome!</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">Please click the button below to verify your email address.</p>

                <div style="display: flex;margin-bottom: 95px">
                    <a href="{{verify_email_url}}" style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;">Verify Email Address</a>
                </div>
                HTML,
            ], [
                'code' => 'shop.customers.subscribed',
                'name' => 'Newsletter Subscription — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">Welcome to our newsletter!</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">Congratulations and welcome to our newsletter community! We're excited to have you on board and keep you updated with the latest news, trends, and exclusive offers.</p>

                <div style="display: flex;margin-bottom: 95px">
                    <a href="{{unsubscribe_url}}" style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;">Unsubscribe</a>
                </div>
                HTML,
            ], [
                'code' => 'shop.customers.note',
                'name' => 'Customer Note — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">Note Is - {{note}}</p>
                HTML,
            ], [
                'code' => 'shop.customers.invoice-reminder',
                'name' => 'Invoice Overdue Reminder — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>
                </div>

                <div>
                    <p>This is a gentle reminder that your invoice is now overdue. We kindly request you to make the payment at your earliest convenience.</p>

                    <p style="margin-top: 20px;">If you have already made the payment, please disregard this message.</p>
                </div>
                HTML,
            ], [
                'code' => 'shop.customers.forgot-password',
                'name' => 'Forgot Password — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">Forgot Password!</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">You are receiving this email because we received a password reset request for your account.</p>

                <div style="display: flex;margin-bottom: 95px">
                    <a href="{{reset_password_url}}" style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;">Reset Password</a>
                </div>
                HTML,
            ], [
                'code' => 'shop.customers.gdpr.new-request',
                'name' => 'New GDPR Request — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>
                </div>

                <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
                    <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">{{request_summary}}</div>
                </div>

                <div style="flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 20px;">
                    <div style="line-height: 25px;font-size: 16px;color: #242424">
                        <span style="font-weight: bold;">Request Status : </span> {{request_status}}
                    </div>

                    <div style="line-height: 25px; font-size: 16px;color: #242424;">
                        <div>
                            <span style="font-weight: bold;">Request Type : </span> {{request_type}}
                        </div>

                        <div>
                            <span style="font-weight: bold">Message : </span> {{message}}
                        </div>
                    </div>
                </div>
                HTML,
            ], [
                'code' => 'shop.customers.gdpr.status-update',
                'name' => 'GDPR Request Status Update — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold; font-size: 20px; color: #121A26; line-height: 24px; margin-bottom: 24px;">Dear {{customer_name}}, 👋</p>
                </div>

                <div style="font-size: 20px; color: #242424; line-height: 30px; margin-bottom: 34px;">
                    <div style="font-weight: bold; font-size: 20px; color: #242424; line-height: 30px; margin-bottom: 20px !important;">Your GDPR Request Status has been Updated</div>
                </div>

                <div style="flex-direction: row; margin-top: 20px; justify-content: space-between; margin-bottom: 20px;">
                    <div style="line-height: 25px; font-size: 16px; color: #242424;">
                        <span style="font-weight: bold;">Request Status:</span> {{request_status}}
                    </div>

                    <div style="line-height: 25px; font-size: 16px; color: #242424;">
                        <div>
                            <span style="font-weight: bold;">Request Type:</span> {{request_type}}
                        </div>

                        <div>
                            <span style="font-weight: bold;">Message:</span> {{message}}
                        </div>
                    </div>
                </div>
                HTML,
            ], [
                'code' => 'shop.customers.rma.new-request',
                'name' => 'New RMA Request — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 40px;">
                    <h1 style="font-size: 28px; font-weight: 700; color: #121A26; margin: 0 0 20px 0;">RMA Request</h1>

                    <p style="font-size: 16px; color: #5E5E5E; line-height: 26px; margin: 0 0 16px 0;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px; color: #5E5E5E; line-height: 26px; margin: 0;">You requested new RMA for order {{order_id}}.</p>
                </div>

                {{rma_details}}
                HTML,
            ], [
                'code' => 'shop.customers.rma.status',
                'name' => 'RMA Status Updated — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Status Updated!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Your RMA Id {{rma_id}} status has been changed by Admin</p>

                    <div style="margin-bottom: 20px; margin-top: 20px; display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="line-height: 25px;">
                            <div style="font-size: 16px; font-weight: bold; color: #242424;">
                                Status :
                                <span style="font-size: 16px; color: #5E5E5E; line-height: 24px;">{{rma_status}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                HTML,
            ], [
                'code' => 'shop.customers.rma.conversation',
                'name' => 'RMA New Message — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px; font-weight: 600; color: #121A26;">Message Received!</span><br>

                    <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px; color: #6B7280; line-height: 24px;">There is a new message from Admin</p>

                    <div style="margin-bottom: 20px; margin-top: 20px; display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="line-height: 25px;">
                            <div style="font-size: 16px; font-weight: bold; color: #1F2937;">Message</div>

                            <div style="font-size: 16px; color: #6B7280;">{{message}}</div>
                        </div>
                    </div>
                </div>
                HTML,
            ], [
                'code' => 'shop.customers.eu-withdrawal.confirmation',
                'name' => 'EU Withdrawal Confirmation — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26;">{{title}}</span> <br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">{{intro}}</p>
                </div>

                {{withdrawal_details}}
                HTML,
            ], [
                'code' => 'shop.customers.eu-withdrawal.guest-link',
                'name' => 'EU Withdrawal Guest Link — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26;">Your withdrawal link</span> <br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Use the link below to file your withdrawal for order {{order_id}}. The link is valid for 24 hours.</p>
                </div>

                <div style="margin-bottom: 40px;">
                    <a href="{{withdrawal_link}}" style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;display: inline-block;">Open the withdrawal form</a>
                </div>

                <p style="font-size: 13px;color: #8A94A6;line-height: 20px;margin-bottom: 8px;">The link will expire in 24 hours. If you did not request this email, you can safely ignore it.</p>
                HTML,
            ], [
                'code' => 'shop.contact-us',
                'name' => 'Contact Us Form Submission',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-size: 16px;color: #384860;line-height: 24px;">{{message}}</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">To contact {{email}}, please reply to this email.</p>
                HTML,
            ], [
                'code' => 'admin.orders.inventory-source',
                'name' => 'Order Shipped — Inventory Source Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Shipped!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{contact_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">You have shipped the order {{order_id}} placed on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'admin.customers.new-customer',
                'name' => 'New Customer Created by Admin — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">Welcome and thank you for registering with us!</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;">Your account has been created. Your account details are below:</p>

                <p style="font-size: 16px;color: #384860;line-height: 24px;">Username/Email : {{customer_email}}</p>

                <p style="font-size: 16px;color: #384860;line-height: 24px;">Password : {{password}}</p>

                <div style="display: flex;margin-bottom: 95px">
                    <a href="{{sign_in_url}}" style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;">Sign in</a>
                </div>
                HTML,
            ], [
                'code' => 'admin.customers.gdpr.new-request',
                'name' => 'New GDPR Request — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{admin_name}}, 👋</p>
                </div>

                <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
                    <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">{{request_summary}}</div>
                </div>

                <div style="flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 20px;">
                    <div style="line-height: 25px;font-size: 16px;color: #242424">
                        <span style="font-weight: bold;">Customer Name : </span> {{customer_name}}
                    </div>

                    <div style="line-height: 25px;font-size: 16px;color: #242424">
                        <span style="font-weight: bold;">Request Status : </span> {{request_status}}
                    </div>

                    <div style="line-height: 25px; font-size: 16px;color: #242424;">
                        <div>
                            <span style="font-weight: bold;">Request Type : </span> {{request_type}}
                        </div>

                        <div>
                            <span style="font-weight: bold">Message : </span> {{message}}
                        </div>
                    </div>
                </div>
                HTML,
            ], [
                'code' => 'admin.customers.gdpr.status-update',
                'name' => 'GDPR Request Status Update — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold; font-size: 20px; color: #121A26; line-height: 24px; margin-bottom: 24px;">Dear {{admin_name}}, 👋</p>
                </div>

                <div style="font-size: 20px; color: #242424; line-height: 30px; margin-bottom: 34px;">
                    <div style="font-weight: bold; font-size: 20px; color: #242424; line-height: 30px; margin-bottom: 20px !important;">The GDPR Request Status Has Been Updated</div>
                </div>

                <div style="flex-direction: row; margin-top: 20px; justify-content: space-between; margin-bottom: 20px;">
                    <div style="line-height: 25px; font-size: 16px; color: #242424;">
                        <span style="font-weight: bold;">Request Status:</span> {{request_status}}
                    </div>

                    <div style="line-height: 25px; font-size: 16px; color: #242424;">
                        <div>
                            <span style="font-weight: bold;">Request Type:</span> {{request_type}}
                        </div>

                        <div>
                            <span style="font-weight: bold;">Message:</span> {{message}}
                        </div>
                    </div>
                </div>
                HTML,
            ], [
                'code' => 'admin.reset-password',
                'name' => 'Forgot Password — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{admin_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">Forgot Password!</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">You are receiving this email because we received a password reset request for your account.</p>

                <div style="display: flex;margin-bottom: 95px">
                    <a href="{{reset_password_url}}" style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;">Reset Password</a>
                </div>
                HTML,
            ], [
                'code' => 'admin.backup-codes',
                'name' => 'Two-Factor Backup Codes — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{admin_name}},</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">You have successfully enabled Two-Factor Authentication for your admin account.</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">For your security, we have generated backup codes that you can use if you lose access to your authenticator app. Each code can only be used once.</p>

                <div style="margin-bottom: 40px;">
                    <p style="font-weight: bold;font-size: 18px;color: #121A26;line-height: 24px;margin-bottom: 16px">Your Backup Codes</p>

                    <p style="font-size: 14px;color: #384860;line-height: 20px;margin-bottom: 20px">Store these codes in a safe place - each can only be used once.</p>

                    {{backup_codes}}
                </div>

                <div style="background: #FFF3CD;border: 1px solid #F59E0B;border-radius: 4px;padding: 20px;margin-bottom: 40px;">
                    <p style="font-weight: bold;font-size: 16px;color: #92400E;line-height: 24px;margin-bottom: 8px;">Important Security Notice</p>

                    <p style="font-size: 14px;color: #92400E;line-height: 20px;margin: 0;">Keep these codes secure and do not share them with anyone. Store them offline in a safe location.</p>
                </div>
                HTML,
            ], [
                'code' => 'admin.rma.conversation',
                'name' => 'RMA New Message — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px; font-weight: 600; color: #121A26;">New RMA Conversation</span><br>

                    <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">Hello {{admin_name}}, 👋</p>

                    <p style="font-size: 16px; color: #6B7280; line-height: 24px;">You have received a new message in your RMA conversation.</p>

                    <div style="margin-bottom: 20px; margin-top: 20px; display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="line-height: 25px;">
                            <div style="font-size: 16px; font-weight: bold; color: #1F2937;">Message</div>

                            <div>{{message}}</div>
                        </div>
                    </div>
                </div>
                HTML,
            ],
        ];
    }
};
