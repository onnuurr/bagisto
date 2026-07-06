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
        Schema::create('gift_card_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gift_card_id')->unsigned();
            $table->string('action');
            $table->integer('order_id')->unsigned()->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('gift_card_id')->references('id')->on('gift_cards')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_card_histories');
    }
};
