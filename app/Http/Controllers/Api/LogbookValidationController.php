<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\LogbookValidation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class LogbookValidationController extends Controller
{
    use ApiResponse;
    public function pending(Request $request){
        $fs=$request->user()->fieldSupervisor;
        $q=Logbook::with(['student.user','assignment.company'])->whereHas('assignment',fn($a)=>$a->where('field_supervisor_id',$fs?->id))->where('status','pending');
        return $this->paginatedResponse('Data logbook menunggu validasi berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));
    }
    private function validateLogbook(Request $request,$id,string $status,string $message){
        $data=$request->validate(['note'=>'nullable|string']);$logbook=Logbook::findOrFail($id);$fs=$request->user()->fieldSupervisor;
        $logbook->update(['status'=>$status]);
        $validation=LogbookValidation::create(['logbook_id'=>$logbook->id,'field_supervisor_id'=>$fs->id,'status'=>$status,'note'=>$data['note']??null,'validated_at'=>now()]);
        return $this->successResponse($message,$logbook->load(['latestValidation','attachments']));
    }
    public function approve(Request $request,$id){return $this->validateLogbook($request,$id,'approved','Logbook berhasil disetujui');}
    public function revise(Request $request,$id){return $this->validateLogbook($request,$id,'revision','Logbook diminta untuk direvisi');}
    public function reject(Request $request,$id){return $this->validateLogbook($request,$id,'rejected','Logbook berhasil ditolak');}
}
