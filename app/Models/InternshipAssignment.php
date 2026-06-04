<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class InternshipAssignment extends Model
{
    use HasFactory;
    protected $fillable = ['student_id','company_id','lecturer_id','field_supervisor_id','period_id','start_date','end_date','status'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function company(){ return $this->belongsTo(Company::class); }
    public function lecturer(){ return $this->belongsTo(Lecturer::class); }
    public function fieldSupervisor(){ return $this->belongsTo(FieldSupervisor::class); }
    public function period(){ return $this->belongsTo(InternshipPeriod::class, 'period_id'); }
    public function logbooks(){ return $this->hasMany(Logbook::class, 'assignment_id'); }
    public function warnings()
    {
        return $this->hasMany(Warning::class, 'assignment_id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'assignment_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'assignment_id');
    }
}
