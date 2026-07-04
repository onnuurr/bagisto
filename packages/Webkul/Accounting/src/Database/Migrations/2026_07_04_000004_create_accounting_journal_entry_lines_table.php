<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_journal_entry_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('journal_entry_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->decimal('debit', 12, 4)->default(0);
            $table->decimal('credit', 12, 4)->default(0);
            $table->decimal('base_debit', 12, 4)->default(0);
            $table->decimal('base_credit', 12, 4)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('journal_entry_id')->references('id')->on('accounting_journal_entries')->onDelete('cascade');
            $table->index('account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_journal_entry_lines');
    }
};
