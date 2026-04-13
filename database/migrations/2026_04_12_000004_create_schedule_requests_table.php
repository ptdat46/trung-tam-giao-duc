<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('session_id')->nullable()
                  ->constrained('course_sessions')->onDelete('set null'); // null for TYPE_ADD_NEW
            $table->unsignedTinyInteger('type'); // 0=Change schedule/room, 1=Add new session

            // Old schedule
            $table->foreignId('old_room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->date('old_session_date')->nullable();
            $table->time('old_start_time')->nullable();

            // New schedule
            $table->foreignId('new_room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->date('new_session_date')->nullable();
            $table->time('new_start_time')->nullable();
            $table->unsignedInteger('new_duration')->nullable(); // minutes

            $table->text('reason')->nullable();
            $table->unsignedTinyInteger('status')->default(0); // 0=Pending, 1=Approved, 2=Rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_requests');
    }
};