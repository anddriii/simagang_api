<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\InternshipAssignment;
use App\Models\Logbook;
use App\Models\LogbookAttachment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class LogbookController extends Controller
{
    use ApiResponse;
    public function index(Request $request){
        $q=Logbook::with(['student.user','assignment.company','attachments','latestValidation']);
        $user=$request->user();
        if($user->role==='student'){$q->where('student_id',$user->student?->id);} 
        if($request->assignment_id){$q->where('assignment_id',$request->assignment_id);} 
        if($request->status){$q->where('status',$request->status);} 
        return $this->paginatedResponse('Data logbook berhasil diambil',$q->latest('activity_date')->paginate($request->integer('per_page',10)));
    }
    public function store(Request $request)
    {
        $student = $request->user()->student;

        if (!$student) {
            return $this->errorResponse('Profil mahasiswa tidak ditemukan', null, 422);
        }

        $data = $request->validate([
            'assignment_id' => 'required|exists:internship_assignments,id',
            'activity_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'problem' => 'nullable|string',
        ]);

        $assignment = InternshipAssignment::query()
            ->where('id', '=', $data['assignment_id'])
            ->where('student_id', '=', $student->id)
            ->first();

        if (!$assignment) {
            return $this->errorResponse('Data penempatan magang tidak ditemukan atau bukan milik mahasiswa ini', null, 404);
        }

        $logbook = Logbook::create([
            'assignment_id' => $data['assignment_id'],
            'student_id' => $student->id,
            'activity_date' => $data['activity_date'],
            'start_time' => $data['start_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'problem' => $data['problem'] ?? null,
            'status' => 'draft',
        ]);

        // return $this->successResponse($logbook, 'Logbook berhasil dibuat', 201);
        return $this->successResponse('Logbook berhasil dibuat', $logbook, 201);
    }

    public function show($id)
    {
        $user = request()->user();

        $logbook = Logbook::with([
            'student.user',
            'assignment.company',
            'assignment.lecturer.user',
            'assignment.fieldSupervisor.user',
            'attachments',
            'validations',
        ])->findOrFail($id);

        if ($user->role === 'student') {
            if ($logbook->student?->user_id !== $user->id) {
                return $this->errorResponse('Akses ditolak', null, 403);
            }
        }

        if ($user->role === 'lecturer') {
            if ($logbook->assignment?->lecturer?->user_id !== $user->id) {
                return $this->errorResponse('Akses ditolak', null, 403);
            }
        }

        if ($user->role === 'field_supervisor') {
            if ($logbook->assignment?->fieldSupervisor?->user_id !== $user->id) {
                return $this->errorResponse('Akses ditolak', null, 403);
            }
        }

        return $this->successResponse('Detail logbook berhasil diambil', $logbook);
    }
    public function update(Request $request, Logbook $logbook){
        if(!in_array($logbook->status,['draft','revision'],true)){return $this->errorResponse('Logbook tidak bisa diubah setelah dikirim/disetujui',null,422);} 
        $data=$request->validate(['activity_date'=>'required|date','start_time'=>'nullable|date_format:H:i','end_time'=>'nullable|date_format:H:i','title'=>'required|string|max:255','description'=>'required|string','problem'=>'nullable|string']);
        $logbook->update([...$data,'status'=>'draft']);return $this->successResponse('Logbook berhasil diperbarui',$logbook);
    }
    public function destroy(Logbook $logbook){if(!in_array($logbook->status,['draft','revision'],true)){return $this->errorResponse('Logbook tidak bisa dihapus',null,422);} $logbook->delete(['id' => $logbook->id]);return $this->successResponse('Logbook berhasil dihapus');}
    public function uploadAttachment(Request $request,$id){
        $logbook=Logbook::findOrFail($id);$data=$request->validate(['file'=>'required|file|mimes:jpg,jpeg,png,pdf|max:5120']);
        $file=$data['file'];$path=$file->store('logbooks','public');
        $att=LogbookAttachment::create(['logbook_id'=>$logbook->id,'file_name'=>$file->getClientOriginalName(),'file_path'=>$path,'file_url'=>Storage::url($path)]);
        return $this->successResponse('Bukti kegiatan berhasil diupload',$att,201);
    }
    public function submit($id){$logbook=Logbook::findOrFail($id);$logbook->update(['status'=>'pending','submitted_at'=>now()]);return $this->successResponse('Logbook berhasil dikirim untuk validasi',$logbook);}
}
