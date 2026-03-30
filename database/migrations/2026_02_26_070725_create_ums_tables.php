<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->foreignId('created_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->timestamps();
        });

        // Programme Master
        Schema::create('programme_master', function (Blueprint $table) {
            $table->id('programme_id');
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->timestamps();
        });

        // Course Master
        Schema::create('course_master', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);

            // relation
            $table->foreignId('programme_id')->constrained('programme_master', 'programme_id')->onDelete('restrict');
            $table->foreignId('created_by')->constrained('users', 'id')->cascadeOnDelete();
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

            // relation
            $table->foreignId('programme_id')->constrained('programme_master', 'programme_id')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('course_master', 'course_id')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->timestamps();
        });

        /* =================================================================
            5. TABLE: student_basic_information (Parent)
        ==================================================================== */
        Schema::create('student_basic_information', function (Blueprint $table) {
            $table->id('student_id');

            // Personal Information
            $table->string('reg_no', 100)->unique();
            $table->string('surname', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('full_name', 255);
            $table->string('mother_name', 100);
            $table->string('father_name', 100);
            $table->string('gender', 20);
            $table->date('dob');
            $table->string('abc_id', 50)->nullable();
            $table->string('aadhaar_no', 20)->unique();
            $table->string('nationality', 50)->default('Indian');
            $table->string('domicile', 100);
            $table->string('mobile_no', 20);
            $table->string('email', 150)->unique();
            $table->string('religion', 50)->nullable();
            $table->string('category', 50); // OPEN, OBC, SC, ST
            $table->string('caste', 100)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('marital_status', 20)->default('Single');
            $table->decimal('annual_family_income', 10, 2)->nullable();
            $table->string('parent_mobile', 20)->nullable();

            // Boolean Flags
            $table->boolean('is_blind')->default(0);
            $table->boolean('is_bpl')->default(0);
            $table->boolean('is_minority')->default(0);
            $table->boolean('is_ph')->default(0);

            // Address (Present)
            $table->string('present_address_1', 255);
            $table->string('present_address_2', 255)->nullable();
            $table->string('present_city', 100);
            $table->string('present_country', 100)->default('India');
            $table->string('present_state', 100);
            $table->string('present_district', 100);
            $table->string('present_pin', 20);

            // Address (Permanent)
            $table->string('permanent_address_1', 255);
            $table->string('permanent_address_2', 255)->nullable();
            $table->string('permanent_city', 100);
            $table->string('permanent_country', 100)->default('India');
            $table->string('permanent_state', 100);
            $table->string('permanent_district', 100);
            $table->string('permanent_pin', 20);

            // Previous Examination
            $table->string('admission_type', 50);
            $table->string('exam_name', 150);
            $table->string('passing_month_year', 50);
            $table->string('board_type', 100);
            $table->string('institution_name', 255);
            $table->string('board_university_name', 255);
            $table->string('division_class', 50)->nullable();
            $table->decimal('max_marks', 8, 2)->nullable();
            $table->decimal('obtained_marks', 8, 2)->nullable();
            $table->string('grade_cgpa', 20)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();

            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        /* =================================================================
           6. TABLE: student_paper_selection (Child)
        ==================================================================== */
        Schema::create('student_paper_selection', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->unique(); // 1-to-1 relationship
            $table->unsignedBigInteger('programme_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('batch_id');
            // Future: JSON column or pivot table for specific subjects chosen
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('student_basic_information')->onDelete('cascade');
        });

        /* =================================================================
           7. TABLE: student_upload_document (Child)
        ==================================================================== */
        Schema::create('student_upload_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->unique();
            $table->string('photo_path', 255)->nullable();
            $table->string('signature_path', 255)->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('student_basic_information')->onDelete('cascade');
        });

        /* =================================================================
           8. TABLE: student_payment_information (Child)
        ==================================================================== */
        Schema::create('student_payment_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id', 100)->unique();
            $table->string('payment_status', 50); // Pending, Success, Failed
            $table->dateTime('payment_date');
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('student_basic_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payment_information');
        Schema::dropIfExists('student_upload_document');
        Schema::dropIfExists('student_paper_selection');
        Schema::dropIfExists('student_basic_information');
        Schema::dropIfExists('subject_master');
        Schema::dropIfExists('course_master');
        Schema::dropIfExists('programme_master');
        Schema::dropIfExists('batch_master');
    }
};
