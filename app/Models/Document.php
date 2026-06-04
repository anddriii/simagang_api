<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Document extends Model
{
    use HasFactory;
    protected $fillable = ['assignment_id','user_id','type','file_name','file_path','file_url'];
    public function assignment(){ return $this->belongsTo(InternshipAssignment::class, 'assignment_id'); }
    public function user(){ return $this->belongsTo(User::class); }
}
