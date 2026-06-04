<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\InternshipApplicationDocument;
use App\Models\InternshipAssignment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class InternshipApplicationController extends Controller
{
    use ApiResponse;
    public function index(Request $request){
        $q=InternshipApplication::with(['student.user','company','period']);
        if($request->user()->role==='student'){$q->where('student_id',$request->user()->student?->id);}
        if($request->status){$q->where('status',$request->status);}
        return $this->paginatedResponse('Data pengajuan magang berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));
    }
    public function store(Request $request){
        $user = $request->user();
        $student=$request->user()->student;
         if ($user->role !== 'student') {
            return $this->errorResponse('Hanya mahasiswa yang dapat mengajukan magang', null, 403);
        }

        if (!$student) {
            return $this->errorResponse('Profil mahasiswa tidak ditemukan', null, 404);
        }

        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'period_id' => ['required', 'exists:internship_periods,id'],
            'reason' => ['required', 'string'],
        ]);

        $app = InternshipApplication::create([
            'student_id' => $student->id,
            'company_id' => $validated['company_id'],
            'period_id' => $validated['period_id'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);
        $data=$request->validate(['company_id'=>'required|exists:companies,id','period_id'=>'required|exists:internship_periods,id','reason'=>'nullable|string']);
        $app=InternshipApplication::create(['student_id'=>$student->id,'company_id'=>$data['company_id'],'period_id'=>$data['period_id'],'reason'=>$data['reason']??null,'status'=>'pending']);
        return $this->successResponse('Pengajuan magang berhasil dikirim',$app->load(['student.user','company','period']),201);
    }
    public function show($id){$app=InternshipApplication::with(['student.user','company','period','documents'])->findOrFail($id);return $this->successResponse('Detail pengajuan magang berhasil diambil',$app);}
    public function uploadDocument(Request $request,$id){
        $app=InternshipApplication::findOrFail($id);
        $data=$request->validate(['file'=>'required|file|max:5120','type'=>'required|string|max:100']);
        $file=$data['file'];$path=$file->store('internship-applications','public');
        $doc=InternshipApplicationDocument::create(['application_id'=>$app->id,'type'=>$data['type'],'file_name'=>$file->getClientOriginalName(),'file_path'=>$path,'file_url'=>Storage::url($path)]);
        return $this->successResponse('Dokumen berhasil diupload',$doc,201);
    }
    public function approve(Request $request,$id){
        $app=InternshipApplication::findOrFail($id);
        $data=$request->validate(['lecturer_id'=>'required|exists:lecturers,id','field_supervisor_id'=>'required|exists:field_supervisors,id','start_date'=>'required|date','end_date'=>'required|date|after_or_equal:start_date','note'=>'nullable|string']);
        $app->update(['status'=>'approved','note'=>$data['note']??'Pengajuan disetujui']);
        $assignment=InternshipAssignment::create(['student_id'=>$app->student_id,'company_id'=>$app->company_id,'period_id'=>$app->period_id,'lecturer_id'=>$data['lecturer_id'],'field_supervisor_id'=>$data['field_supervisor_id'],'start_date'=>$data['start_date'],'end_date'=>$data['end_date'],'status'=>'active']);
        return $this->successResponse('Pengajuan magang berhasil disetujui',['application'=>$app,'assignment'=>$assignment],201);
    }
    public function reject(Request $request,$id){$data=$request->validate(['note'=>'required|string']);$app=InternshipApplication::findOrFail($id);$app->update(['status'=>'rejected','note'=>$data['note']]);return $this->successResponse('Pengajuan magang berhasil ditolak',$app);}
}
