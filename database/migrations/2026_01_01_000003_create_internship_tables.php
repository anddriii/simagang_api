<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained('internship_periods')->cascadeOnDelete();
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('internship_application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('internship_applications')->cascadeOnDelete();
            $table->string('type');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url')->nullable();
            $table->timestamps();
        });

        Schema::create('internship_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('field_supervisor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained('internship_periods')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_assignments');
        Schema::dropIfExists('internship_application_documents');
        Schema::dropIfExists('internship_applications');
    }
};
