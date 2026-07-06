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
                'code' => 'shop.orders.created',
                'name' => 'Order Created — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Confirmation!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Thanks for your Order {{order_id}} placed on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'shop.orders.canceled',
                'name' => 'Order Canceled — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Canceled!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Your Order {{order_id}} placed on {{order_date}} has been canceled</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'shop.orders.shipped',
                'name' => 'Order Shipped — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Shipped!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Your order {{order_id}} placed on {{order_date}} has been shipped</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'shop.orders.invoiced',
                'name' => 'Order Invoiced — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Invoice Confirmation!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Your invoice #{{invoice_id}} for Order {{order_id}} created on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'shop.orders.refunded',
                'name' => 'Order Refunded — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Refunded!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Refund has been initiated for the {{order_id}} placed on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'shop.orders.commented',
                'name' => 'Order Commented — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">New comment added to your order {{order_id}} placed on {{order_date}}</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">{{comment}}</p>
                HTML,
            ], [
                'code' => 'shop.customers.registration',
                'name' => 'Customer Registration — Customer Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{customer_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">Welcome and thank you for registering with us!</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">Your account has now been created successfully and you can login using your email address and password credentials. Upon logging in, you will be able to access other services including reviewing past orders, wishlists and editing your account information.</p>

                <div style="display: flex;margin-bottom: 95px">
                    <a href="{{sign_in_url}}" style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;">Sign in</a>
                </div>
                HTML,
            ], [
                'code' => 'admin.orders.created',
                'name' => 'Order Created — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Confirmation!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{admin_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">You have a new Order {{order_id}} placed on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'admin.orders.canceled',
                'name' => 'Order Canceled — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Canceled!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{admin_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">You have canceled the order {{order_id}} placed on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'admin.orders.shipped',
                'name' => 'Order Shipped — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Shipped!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{admin_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">You have shipped the order {{order_id}} placed on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'admin.orders.invoiced',
                'name' => 'Order Invoiced — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Invoice Confirmation!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{admin_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Your invoice #{{invoice_id}} for order {{order_id}} created on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'admin.orders.refunded',
                'name' => 'Order Refunded — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <span style="font-size: 22px;font-weight: 600;color: #121A26">Order Refunded!</span><br>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">Dear {{admin_name}}, 👋</p>

                    <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">You have refunded for order {{order_id}} placed on {{order_date}}</p>
                </div>

                {{order_details}}
                HTML,
            ], [
                'code' => 'admin.customers.registration',
                'name' => 'Customer Registration — Admin Email',
                'subject' => null,
                'content' => <<<'HTML'
                <div style="margin-bottom: 34px;">
                    <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">Dear {{admin_name}}, 👋</p>

                    <p style="font-size: 16px;color: #384860;line-height: 24px;">We extend a warm welcome to the new customer, {{customer_name}} who has just registered with us!</p>
                </div>

                <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">A new customer account has been successfully created. They can now log in using their email address and password credentials. Once logged in, they will have access to various services, including the ability to review past orders, manage wishlists, and update their account information.</p>
                HTML,
            ],
        ];
    }
};
