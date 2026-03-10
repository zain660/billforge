<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up()
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly, etc.

            // Gateway specific Ids (e.g. Price IDs)
            $table->string('stripe_price_id')->nullable();
            $table->string('paypal_plan_id')->nullable();
            $table->string('authorize_plan_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->nullable(); // stripe, paypal, authorize_net
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->text('public_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->text('webhook_secret')->nullable();
            $table->text('additional_settings')->nullable(); // JSON for extra config
            $table->timestamps();
        });
        Schema::create('package_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('subscription_packages')->onDelete('cascade');
            $table->string('route_name')->nullable();
            $table->string('route_uri')->nullable();
            $table->timestamps();
        });
        Schema::create('subscription_gateways', function (Blueprint $table) {
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

        Schema::create('subscription_package_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('subscription_packages')->onDelete('cascade');
            $table->string('route_name')->nullable();
            $table->string('route_uri')->nullable();
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Assuming standard users table
            $table->foreignId('package_id')->constrained('subscription_packages')->onDelete('cascade');
            $table->string('gateway_key'); // Which gateway was used
            $table->string('gateway_subscription_id')->nullable();
            $table->string('status'); // active, cancelled, past_due, etc.
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('gateway_settings');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_gateways');
        Schema::dropIfExists('subscription_packages');
        Schema::dropIfExists('package_routes');
    }
};
