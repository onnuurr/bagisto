<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->decimal('amount', 12, 4);
            $table->string('currency');
            $table->string('status')->default('unused');
            $table->dateTime('expires_at')->nullable();
            $table->string('customer_email')->nullable();
            $table->integer('cart_id')->unsigned()->nullable();
            $table->integer('order_id')->unsigned()->nullable();
            $table->dateTime('used_at')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_cards');
    }
};
