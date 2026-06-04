<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class AssessmentController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=Assessment::with(['assignment.student.user','assignment.company','fieldSupervisor.user']);if($request->assignment_id){$q->where('assignment_id',$request->assignment_id);}return $this->paginatedResponse('Data penilaian berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));}
    public function store(Request $request){$data=$this->validated($request);$avg=collect($data)->only(['discipline_score','communication_score','technical_score','responsibility_score','adaptability_score','initiative_score'])->avg();$assessment=Assessment::create([...$data,'field_supervisor_id'=>$request->user()->fieldSupervisor?->id,'average_score'=>round($avg,2)]);return $this->successResponse('Penilaian berhasil disimpan',$assessment,201);}
    public function show($id){return $this->successResponse('Detail penilaian berhasil diambil',Assessment::with(['assignment.student.user','assignment.company','fieldSupervisor.user'])->findOrFail($id));}
    public function update(Request $request,$id){$assessment=Assessment::findOrFail($id);$data=$this->validated($request);$avg=collect($data)->only(['discipline_score','communication_score','technical_score','responsibility_score','adaptability_score','initiative_score'])->avg();$assessment->update([...$data,'average_score'=>round($avg,2)]);return $this->successResponse('Penilaian berhasil diperbarui',$assessment);}
    private function validated(Request $request){return $request->validate(['assignment_id'=>'required|exists:internship_assignments,id','discipline_score'=>'required|integer|min:0|max:100','communication_score'=>'required|integer|min:0|max:100','technical_score'=>'required|integer|min:0|max:100','responsibility_score'=>'required|integer|min:0|max:100','adaptability_score'=>'required|integer|min:0|max:100','initiative_score'=>'required|integer|min:0|max:100','final_note'=>'nullable|string']);}
}
