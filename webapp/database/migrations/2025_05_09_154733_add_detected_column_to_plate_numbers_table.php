<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // In the new migration file
    public function up()
    {
        Schema::table('plate_numbers', function (Blueprint $table) {
            if (!Schema::hasColumn('plate_numbers', 'detected')) {
                $table->boolean('detected')->default(1)->after('date_time_scanned');
            }
        });
    }

    public function down()
    {
        Schema::table('plate_numbers', function (Blueprint $table) {
            if (Schema::hasColumn('plate_numbers', 'detected')) {
                $table->dropColumn('detected');
            }
        });
    }
};
