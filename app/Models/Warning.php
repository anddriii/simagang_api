<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Warning extends Model
{
    use HasFactory;
    protected $fillable = ['student_id','assignment_id','warning_type','level','message','status','resolved_note','resolved_at'];
    protected $casts = ['resolved_at' => 'datetime'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function assignment(){ return $this->belongsTo(InternshipAssignment::class, 'assignment_id'); }
}
