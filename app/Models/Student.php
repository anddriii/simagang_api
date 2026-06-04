<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Student extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','nim','study_program','class','semester'];
    public function user(){ return $this->belongsTo(User::class); }
    public function applications(){ return $this->hasMany(InternshipApplication::class); }
    public function assignments(){ return $this->hasMany(InternshipAssignment::class); }
    public function logbooks(){ return $this->hasMany(Logbook::class); }
}
