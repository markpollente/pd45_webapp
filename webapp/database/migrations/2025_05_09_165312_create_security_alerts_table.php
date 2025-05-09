<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('security_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('plate_number');
            $table->string('registered_driver');
            $table->string('detected_driver');
            $table->timestamp('timestamp');
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->boolean('resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('security_alerts');
    }
};