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
        if (Schema::hasTable('gateway_settings')) {
            return;
        }
        Schema::create('gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // stripe, paypal, authorize_net
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->text('public_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->text('webhook_secret')->nullable();
            $table->text('additional_settings')->nullable(); // JSON for extra config
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    //
    }
};
