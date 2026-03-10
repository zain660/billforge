<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // e.g. 20.00 for 20% or $20
            $table->integer('max_uses')->nullable(); // null means infinite
            $table->integer('used_count')->default(0);
            $table->timestamp('valid_until')->nullable();
            
            // For Gateway Syncing
            $table->string('stripe_coupon_id')->nullable(); 

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_coupons');
    }
};
