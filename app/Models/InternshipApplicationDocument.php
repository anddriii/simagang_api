<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class InternshipApplicationDocument extends Model
{
    use HasFactory;
    protected $fillable = ['application_id','type','file_name','file_path','file_url'];
    public function application(){ return $this->belongsTo(InternshipApplication::class, 'application_id'); }
}
