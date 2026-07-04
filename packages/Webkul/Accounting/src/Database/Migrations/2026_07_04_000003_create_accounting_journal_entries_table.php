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
        Schema::create('accounting_journal_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fiscal_year_id')->unsigned()->nullable();
            $table->string('entry_number')->unique();
            $table->date('entry_date');
            $table->string('reference_type')->nullable();
            $table->integer('reference_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('currency_code');
            $table->decimal('exchange_rate', 12, 6)->default(1);
            $table->string('status')->default('draft');
            $table->timestamp('posted_at')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('fiscal_year_id')->references('id')->on('accounting_fiscal_years')->onDelete('set null');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_journal_entries');
    }
};
