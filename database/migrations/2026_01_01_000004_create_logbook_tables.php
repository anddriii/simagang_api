<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('internship_assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('activity_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('problem')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('logbook_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_id')->constrained()->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url')->nullable();
            $table->timestamps();
        });

        Schema::create('logbook_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_id')->constrained()->cascadeOnDelete();
            $table->foreignId('field_supervisor_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_validations');
        Schema::dropIfExists('logbook_attachments');
        Schema::dropIfExists('logbooks');
    }
};
