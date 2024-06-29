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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary()->index();
            $table->integer('total_quantity')->nullable();
            $table->integer('total_amount')->nullable();
            $table->enum('status',['pending', 'in-progress', 'completed', 'canceled', 'delivered'])->default('pending');
            $table->longText('delivery_address')->nullable();
            $table->string('pickup_location_id')->index()->nullable();
            $table->string('state_id')->index()->nullable();
            $table->string('user_id')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
