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
        Schema::create('payments', function (Blueprint $table) {
            $table->string('id')->primary()->index();
            $table->string('tx_ref')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('description')->nullable();
            $table->string('amount')->nullable();
            $table->string('payment_options')->nullable();
            $table->string('currency')->nullable();
            $table->enum('status', ['pending', 'rejected', 'success', 'cancelled'])->default('pending');
            $table->string('channel')->nullable();
            $table->string('order_id')->index()->nullable();
            $table->string('user_id')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
