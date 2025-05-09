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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('CASCADE');

            $table->string('amount')->nullable();
            $table->text('desc')->nullable();
            $table->enum('status', ['paid', 'billed'])->default('billed');
            $table->enum('type', ['planned', 'canceled'])->default('planned');
            $table->enum('kind', ['check', 'deposit'])->default('deposit');
            $table->boolean('collectible')->default(true);
            $table->dateTime('due_at')->nullable();
            $table->dateTime('postponed_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
