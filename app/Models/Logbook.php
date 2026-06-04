<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Logbook extends Model
{
    use HasFactory;
    protected $fillable = ['assignment_id','student_id','activity_date','start_time','end_time','title','description','problem','status','submitted_at'];
    protected $casts = ['activity_date' => 'date', 'submitted_at' => 'datetime'];
    public function assignment(){ return $this->belongsTo(InternshipAssignment::class, 'assignment_id'); }
    public function student(){ return $this->belongsTo(Student::class); }
    public function attachments(){ return $this->hasMany(LogbookAttachment::class); }
    public function validations(){ return $this->hasMany(LogbookValidation::class); }
    public function latestValidation(){ return $this->hasOne(LogbookValidation::class)->latestOfMany(); }
}
