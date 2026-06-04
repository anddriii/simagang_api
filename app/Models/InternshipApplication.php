<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class InternshipApplication extends Model
{
    use HasFactory;
    protected $fillable = ['student_id','company_id','period_id','reason','status','note'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function company(){ return $this->belongsTo(Company::class); }
    public function period(){ return $this->belongsTo(InternshipPeriod::class, 'period_id'); }
    public function documents(){ return $this->hasMany(InternshipApplicationDocument::class, 'application_id'); }
}
