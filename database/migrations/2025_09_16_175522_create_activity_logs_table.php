<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(config('activity-logger.table', 'activity_logs'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->index();
            $table->string('action');
            $table->string('loggable_type');
            $table->unsignedBigInteger('loggable_id');
            $table->longText('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index(['loggable_type', 'loggable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('activity-logger.table', 'activity_logs'));
    }
};
