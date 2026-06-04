<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\InternshipAssignment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class InternshipAssignmentController extends Controller
{
    use ApiResponse;
    public function index(Request $request){
        $q=InternshipAssignment::with(['student.user','company','lecturer.user','fieldSupervisor.user','period']);
        $user=$request->user();
        if($user->role==='student'){$q->where('student_id',$user->student?->id);} 
        if($user->role==='lecturer'){$q->where('lecturer_id',$user->lecturer?->id);} 
        if($user->role==='field_supervisor'){$q->where('field_supervisor_id',$user->fieldSupervisor?->id);} 
        if($request->status){$q->where('status',$request->status);} 
        return $this->paginatedResponse('Data penempatan magang berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));
    }
    public function show($id){$assignment=InternshipAssignment::with(['student.user','company','lecturer.user','fieldSupervisor.user','period','logbooks'])->findOrFail($id);return $this->successResponse('Detail penempatan magang berhasil diambil',$assignment);}
}
