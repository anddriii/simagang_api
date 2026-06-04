<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }

    public function fieldSupervisor()
    {
        return $this->hasOne(FieldSupervisor::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function consultationReplies()
    {
        return $this->hasMany(ConsultationReply::class, 'sender_id');
    }

    public function chatbotConversations()
    {
        return $this->hasMany(ChatbotConversation::class);
    }

    public function chatbotKnowledgeBases()
    {
        return $this->hasMany(ChatbotKnowledgeBase::class, 'created_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}