<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Assessment extends Model
{
    use HasFactory;
    protected $fillable = ['assignment_id','field_supervisor_id','discipline_score','communication_score','technical_score','responsibility_score','adaptability_score','initiative_score','average_score','final_note'];
    public function assignment(){ return $this->belongsTo(InternshipAssignment::class, 'assignment_id'); }
    public function fieldSupervisor(){ return $this->belongsTo(FieldSupervisor::class); }
}
