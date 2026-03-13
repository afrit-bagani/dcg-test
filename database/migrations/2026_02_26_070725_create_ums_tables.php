<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Batch Master
        Schema::create('batch_master', function (Blueprint $table) {
            $table->id('batch_id');
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->string('created_by');
            $table->timestamps();
        });

        // Programme Master
        Schema::create('programme_master', function (Blueprint $table) {
            $table->id('programme_id');
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->string('created_by');
            $table->timestamps();
        });

        // Course Master
        Schema::create('course_master', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->string('created_by');
            $table->timestamps();
        });

        // Subject Master
        Schema::create('subject_master', function (Blueprint $table) {
            $table->id('subject_id');
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('internal_full_marks', 10, 2)->default(0);
            $table->decimal('internal_pass_marks', 10, 2)->default(0);
            $table->decimal('theory_full_marks', 10, 2)->default(0);
            $table->decimal('theory_pass_marks', 10, 2)->default(0);
            $table->decimal('practical_full_marks', 10, 2)->default(0);
            $table->decimal('practical_pass_marks', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('created_by');
            $table->timestamps();
        });

        // student
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('reg_no')->unique();
            $table->string('name');
            $table->string('email')->unique();

            $table->foreignId('batch_id')->constrained('batch_master', 'batch_id');
            $table->foreignId('programme_id')->constrained('programme_master', 'programme_id');
            $table->foreignId('course_id')->constrained('course_master', 'course_id');

            $table->boolean('is_active')->default(1);
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
        Schema::dropIfExists('subject_master');
        Schema::dropIfExists('course_master');
        Schema::dropIfExists('programme_master');
        Schema::dropIfExists('batch_master');
    }
};
