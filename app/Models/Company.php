<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name','address','field','email','phone','quota','status'];
    public function fieldSupervisors(){ return $this->hasMany(FieldSupervisor::class); }
    public function assignments(){ return $this->hasMany(InternshipAssignment::class); }
}
