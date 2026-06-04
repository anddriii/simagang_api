<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\InternshipAssignment;
use App\Models\Warning;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class WarningController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=Warning::with('student.user','assignment.company');if($request->level){$q->where('level',$request->level);}if($request->status){$q->where('status',$request->status);}return $this->paginatedResponse('Data warning berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));}
    public function generate(){
        $count=0;$assignments=InternshipAssignment::with('logbooks')->where('status','active')->get();
        foreach($assignments as $assignment){
            $last=$assignment->logbooks()->latest('activity_date')->first();
            if(!$last || $last->activity_date->diffInDays(now())>=3){
                Warning::firstOrCreate(['student_id'=>$assignment->student_id,'assignment_id'=>$assignment->id,'warning_type'=>'missing_logbook','status'=>'active'],['level'=>'medium','message'=>'Mahasiswa belum mengisi logbook selama 3 hari atau lebih.']);$count++;
            }
            $total=$assignment->logbooks()->count();$approved=$assignment->logbooks()->where('status','approved')->count();
            if($total>=5 && ($approved/max($total,1))*100<50){
                Warning::firstOrCreate(['student_id'=>$assignment->student_id,'assignment_id'=>$assignment->id,'warning_type'=>'low_progress','status'=>'active'],['level'=>'high','message'=>'Progress logbook tervalidasi masih di bawah 50%.']);$count++;
            }
        }
        return $this->successResponse('Early warning berhasil diproses',['generated_warnings'=>$count]);
    }
    public function resolve(Request $request,$id){$data=$request->validate(['note'=>'required|string']);$warning=Warning::findOrFail($id);$warning->update(['status'=>'resolved','resolved_note'=>$data['note'],'resolved_at'=>now()]);return $this->successResponse('Warning berhasil diselesaikan',$warning);}
}
