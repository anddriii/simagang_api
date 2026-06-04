<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Lecturer extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','nidn','department'];
    public function user(){ return $this->belongsTo(User::class); }
    public function assignments(){ return $this->hasMany(InternshipAssignment::class); }
}
