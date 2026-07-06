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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('gift_card_id')->unsigned()->nullable();
            $table->decimal('gift_cards_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_gift_cards_amount', 12, 4)->default(0)->nullable();

            $table->foreign('gift_card_id')->references('id')->on('gift_cards')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['gift_card_id']);
            $table->dropColumn([
                'gift_card_id',
                'gift_cards_amount',
                'base_gift_cards_amount',
            ]);
        });
    }
};
