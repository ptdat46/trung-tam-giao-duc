<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->after('thumbnail');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->integer('max_students')->default(0)->after('meeting_link');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('max_students');
        });
    }
};
