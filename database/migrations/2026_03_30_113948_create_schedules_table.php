<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            $table->string('subject_name');
            $table->string('subject_code')->nullable();
            $table->string('teacher_name')->nullable();
            $table->string('teacher_no')->nullable();
            $table->string('room_no')->nullable();
            $table->string('branch_no')->nullable();

            $table->string('day');
            $table->string('time');

            $table->string('current_smtr')->nullable();
            $table->boolean('exam_access')->default(false);
            $table->text('exam_access_message')->nullable();
            $table->string('online_code')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
