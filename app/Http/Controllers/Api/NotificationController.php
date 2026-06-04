<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class NotificationController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=Notification::where('user_id',$request->user()->id)->latest();return $this->paginatedResponse('Data notifikasi berhasil diambil',$q->paginate($request->integer('per_page',10)));}
    public function read(Request $request,$id){$n=Notification::where('user_id',$request->user()->id)->findOrFail($id);$n->update(['is_read'=>true]);return $this->successResponse('Notifikasi berhasil ditandai sudah dibaca',$n);}
    public function readAll(Request $request){Notification::where('user_id',$request->user()->id)->update(['is_read'=>true]);return $this->successResponse('Semua notifikasi berhasil ditandai sudah dibaca');}
}
