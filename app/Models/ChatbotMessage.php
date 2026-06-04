<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ChatbotMessage extends Model
{
    use HasFactory;
    protected $fillable = ['conversation_id','sender','message','response','source'];
    public function conversation(){ return $this->belongsTo(ChatbotConversation::class, 'conversation_id'); }
}
