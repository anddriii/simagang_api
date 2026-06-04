<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignment_id')->constrained('internship_assignments')->cascadeOnDelete();
            $table->string('warning_type');
            $table->string('level')->default('low');
            $table->text('message');
            $table->string('status')->default('active');
            $table->text('resolved_note')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('open');
            $table->timestamps();
        });

        Schema::create('consultation_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('internship_assignments')->cascadeOnDelete();
            $table->foreignId('field_supervisor_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('discipline_score');
            $table->unsignedTinyInteger('communication_score');
            $table->unsignedTinyInteger('technical_score');
            $table->unsignedTinyInteger('responsibility_score');
            $table->unsignedTinyInteger('adaptability_score');
            $table->unsignedTinyInteger('initiative_score');
            $table->decimal('average_score', 5, 2)->default(0);
            $table->text('final_note')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('chatbot_knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();
            $table->longText('content');
            $table->string('status')->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chatbot_conversations')->cascadeOnDelete();
            $table->string('sender');
            $table->longText('message');
            $table->longText('response')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->nullable()->constrained('internship_assignments')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('chatbot_messages');
        Schema::dropIfExists('chatbot_conversations');
        Schema::dropIfExists('chatbot_knowledge_bases');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('consultation_replies');
        Schema::dropIfExists('consultations');
        Schema::dropIfExists('warnings');
    }
};
