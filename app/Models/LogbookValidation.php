<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class LogbookValidation extends Model
{
    use HasFactory;
    protected $fillable = ['logbook_id','field_supervisor_id','status','note','validated_at'];
    protected $casts = ['validated_at' => 'datetime'];
    public function logbook(){ return $this->belongsTo(Logbook::class); }
    public function fieldSupervisor(){ return $this->belongsTo(FieldSupervisor::class); }
}
