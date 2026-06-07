<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\InternshipAssignment;
use App\Models\Student;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class MonitoringController extends Controller
{
    use ApiResponse;
    public function studentProgress(Request $request, $student_id)
    {
        $student = Student::find($student_id);

        if (!$student) {
            return $this->errorResponse('Data mahasiswa tidak ditemukan', null, 404);
        }

        $user = $request->user();

        if ($user->role === 'student' && $student->user_id !== $user->id) {
            return $this->errorResponse('Akses ditolak', null, 403);
        }

        if ($user->role === 'lecturer') {
            $lecturer = $user->lecturer;

            $isMyStudent = InternshipAssignment::where('student_id', $student->id)
                ->where('lecturer_id', $lecturer?->id)
                ->exists();

            if (!$isMyStudent) {
                return $this->errorResponse('Akses ditolak', null, 403);
            }
        }

        return $this->buildStudentProgressResponse($student);
    }
    public function lecturerMonitoring(Request $request){$lecturer=$request->user()->lecturer;$q=InternshipAssignment::with(['student.user','company','logbooks'])->where('lecturer_id',$lecturer?->id);return $this->paginatedResponse('Data monitoring mahasiswa berhasil diambil',$q->paginate($request->integer('per_page',10)));}
    public function myProgress(Request $request)
    {
        $student = $request->user()->student;

        if (!$student) {
            return $this->errorResponse('Profil mahasiswa tidak ditemukan', null, 404);
        }

        return $this->buildStudentProgressResponse($student);
    }

    private function buildStudentProgressResponse(Student $student)
    {
        $student->loadMissing('user');

        $assignment = InternshipAssignment::with([
            'company',
            'lecturer.user',
            'fieldSupervisor.user',
            'logbooks',
            'warnings',
        ])
            ->where('student_id', $student->id)
            ->latest()
            ->first();

        if (!$assignment) {
            return $this->errorResponse('Data penempatan magang belum tersedia', null, 404);
        }

        $logbooks = $assignment->logbooks;

        $totalLogbooks = $logbooks->count();
        $approvedLogbooks = $logbooks->where('status', 'approved')->count();
        $pendingLogbooks = $logbooks->where('status', 'pending')->count();
        $revisionLogbooks = $logbooks->where('status', 'revision')->count();
        $rejectedLogbooks = $logbooks->where('status', 'rejected')->count();

        $progressPercentage = $totalLogbooks > 0
            ? round(($approvedLogbooks / $totalLogbooks) * 100, 2)
            : 0;

        $activeWarning = $assignment->warnings
            ->where('status', 'active')
            ->sortByDesc('created_at')
            ->first();

        return $this->successResponse('Progress magang berhasil diambil', [
            'student' => [
                'id' => $student->id,
                'name' => $student->user?->name,
                'nim' => $student->nim,
            ],
            'assignment' => [
                'id' => $assignment->id,
                'company' => $assignment->company?->name,
                'lecturer' => $assignment->lecturer?->user?->name,
                'field_supervisor' => $assignment->fieldSupervisor?->user?->name,
                'start_date' => $assignment->start_date ? (string) $assignment->start_date : null,
                'end_date' => $assignment->end_date ? (string) $assignment->end_date : null,
                'status' => $assignment->status,
            ],
            'progress' => [
                'total_logbooks' => $totalLogbooks,
                'approved_logbooks' => $approvedLogbooks,
                'pending_logbooks' => $pendingLogbooks,
                'revision_logbooks' => $revisionLogbooks,
                'rejected_logbooks' => $rejectedLogbooks,
                'progress_percentage' => $progressPercentage,
            ],
            'warning_status' => $activeWarning?->level ?? 'aman',
        ]);
    }
}

