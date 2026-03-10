<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('package_routes', function (Blueprint $table) {
            $table->string('feature_name')->nullable()->after('package_id');
        });
    }

    public function down()
    {
        Schema::table('package_routes', function (Blueprint $table) {
            $table->dropColumn('feature_name');
        });
    }
};
