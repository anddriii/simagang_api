<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Consultation extends Model
{
    use HasFactory;
    protected $fillable = ['student_id','lecturer_id','subject','message','status'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function lecturer(){ return $this->belongsTo(Lecturer::class); }
    public function replies(){ return $this->hasMany(ConsultationReply::class); }
}
