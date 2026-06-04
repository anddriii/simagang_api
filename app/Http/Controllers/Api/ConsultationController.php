<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationReply;
use App\Models\InternshipAssignment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class ConsultationController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=Consultation::with(['student.user','lecturer.user']);$user=$request->user();if($user->role==='student'){$q->where('student_id',$user->student?->id);}if($user->role==='lecturer'){$q->where('lecturer_id',$user->lecturer?->id);}if($request->status){$q->where('status',$request->status);}return $this->paginatedResponse('Data konsultasi berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));}
    public function store(Request $request){$data=$request->validate(['subject'=>'required|string|max:255','message'=>'required|string']);$student=$request->user()->student;$assignment=InternshipAssignment::where('student_id',$student?->id)->latest()->first();if(!$assignment){return $this->errorResponse('Dosen pembimbing belum tersedia karena assignment belum dibuat',null,422);} $c=Consultation::create(['student_id'=>$student->id,'lecturer_id'=>$assignment->lecturer_id,'subject'=>$data['subject'],'message'=>$data['message'],'status'=>'open']);return $this->successResponse('Konsultasi berhasil dikirim',$c->load(['student.user','lecturer.user']),201);}
    public function show($id){$c=Consultation::with(['student.user','lecturer.user','replies.sender'])->findOrFail($id);return $this->successResponse('Detail konsultasi berhasil diambil',$c);}
    public function reply(Request $request,$id){$data=$request->validate(['message'=>'required|string']);$c=Consultation::findOrFail($id);$reply=ConsultationReply::create(['consultation_id'=>$c->id,'sender_id'=>$request->user()->id,'message'=>$data['message']]);return $this->successResponse('Balasan berhasil dikirim',$reply->load('sender'),201);}
    public function close($id){$c=Consultation::findOrFail($id);$c->update(['status'=>'closed']);return $this->successResponse('Konsultasi berhasil ditutup',$c);}
}
