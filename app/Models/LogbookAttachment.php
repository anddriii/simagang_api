<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class LogbookAttachment extends Model
{
    use HasFactory;
    protected $fillable = ['logbook_id','file_name','file_path','file_url'];
    public function logbook(){ return $this->belongsTo(Logbook::class); }
}
