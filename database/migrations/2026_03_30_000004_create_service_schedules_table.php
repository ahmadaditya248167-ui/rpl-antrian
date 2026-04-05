<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->date('date');
            $table->boolean('is_open')->default(true);
            $table->integer('max_quota');
            $table->timestamps();

            $table->unique(['service_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_schedules');
    }
};
