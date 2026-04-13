<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Must drop FK on attendances first (it references course_sessions)
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
        });

        // Drop old course_sessions (migration 2026_04_02_000005 had minimal columns)
        Schema::dropIfExists('course_sessions');

        // Recreate with full schema
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->date('session_date');
            $table->time('start_time'); // e.g. "08:00:00"
            $table->unsignedInteger('duration'); // minutes, e.g. 90, 120
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->unsignedTinyInteger('type')->default(0); // 0=Regular, 1=Exam, 2=Practice
            $table->unsignedTinyInteger('status')->default(0); // 0=Scheduled, 1=In-Progress, 2=Completed, 3=Cancelled
            $table->timestamps();

            $table->index(['session_date', 'room_id']);
            $table->index(['session_date', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_sessions');
    }
};
