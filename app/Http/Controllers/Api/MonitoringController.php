<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\InternshipAssignment;
use App\Models\Student;
use App\Models\Warning;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class MonitoringController extends Controller
{
    use ApiResponse;
    public function studentProgress($student_id){
        $student=Student::with('user')->findOrFail($student_id);
        $assignment=InternshipAssignment::with(['company','lecturer.user','fieldSupervisor.user'])->where('student_id',$student->id)->latest()->first();
        if(!$assignment){return $this->errorResponse('Penempatan magang tidak ditemukan',null,404);} 
        $total=$assignment->logbooks()->count();$approved=$assignment->logbooks()->where('status','approved')->count();
        return $this->successResponse('Progress magang berhasil diambil',['student'=>$student,'assignment'=>$assignment,'progress'=>['submitted_logbooks'=>$total,'approved_logbooks'=>$approved,'revision_logbooks'=>$assignment->logbooks()->where('status','revision')->count(),'rejected_logbooks'=>$assignment->logbooks()->where('status','rejected')->count(),'pending_logbooks'=>$assignment->logbooks()->where('status','pending')->count(),'progress_percentage'=>$total>0?round(($approved/$total)*100,2):0],'warning_status'=>Warning::where('student_id',$student->id)->where('status','active')->latest()->value('level')??'aman']);
    }
    public function lecturerMonitoring(Request $request){$lecturer=$request->user()->lecturer;$q=InternshipAssignment::with(['student.user','company','logbooks'])->where('lecturer_id',$lecturer?->id);return $this->paginatedResponse('Data monitoring mahasiswa berhasil diambil',$q->paginate($request->integer('per_page',10)));}
}
