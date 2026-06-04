<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\FieldSupervisor;
use App\Models\InternshipApplication;
use App\Models\InternshipAssignment;
use App\Models\Lecturer;
use App\Models\Logbook;
use App\Models\Student;
use App\Models\Warning;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function admin()
    {
        return $this->successResponse('Dashboard admin berhasil diambil', [
            'total_students' => Student::count(),
            'total_companies' => Company::count(),
            'total_lecturers' => Lecturer::count(),
            'total_field_supervisors' => FieldSupervisor::count(),
            'active_internships' => InternshipAssignment::where('status', 'active')->count(),
            'pending_applications' => InternshipApplication::where('status', 'pending')->count(),
            'students_with_warning' => Warning::where('status', 'active')->distinct('student_id')->count('student_id'),
        ]);
    }

    public function student(Request $request)
    {
        $student = $request->user()->student;
        $assignment = $student?->assignments()->with(['company', 'lecturer.user', 'fieldSupervisor.user'])->latest()->first();

        if (!$student || !$assignment) {
            return $this->successResponse('Dashboard mahasiswa berhasil diambil', [
                'internship_status' => 'not_started',
                'assignment' => null,
            ]);
        }

        $total = $assignment->logbooks()->count();
        $approved = $assignment->logbooks()->where('status', 'approved')->count();

        return $this->successResponse('Dashboard mahasiswa berhasil diambil', [
            'internship_status' => $assignment->status,
            'company' => $assignment->company,
            'lecturer' => $assignment->lecturer?->user,
            'field_supervisor' => $assignment->fieldSupervisor?->user,
            'logbook_summary' => [
                'submitted' => $total,
                'approved' => $approved,
                'revision' => $assignment->logbooks()->where('status', 'revision')->count(),
                'pending' => $assignment->logbooks()->where('status', 'pending')->count(),
                'rejected' => $assignment->logbooks()->where('status', 'rejected')->count(),
            ],
            'progress_percentage' => $total > 0 ? round(($approved / max($total, 1)) * 100, 2) : 0,
            'warning_status' => Warning::where('student_id', $student->id)->where('status', 'active')->latest()->value('level') ?? 'aman',
        ]);
    }

    public function lecturer(Request $request)
    {
        $lecturer = $request->user()->lecturer;
        $assignmentIds = $lecturer?->assignments()->pluck('id') ?? collect();

        return $this->successResponse('Dashboard dosen berhasil diambil', [
            'total_students' => $lecturer?->assignments()->distinct('student_id')->count('student_id') ?? 0,
            'active_students' => $lecturer?->assignments()->where('status', 'active')->count() ?? 0,
            'students_with_warning' => Warning::whereIn('assignment_id', $assignmentIds)->where('status', 'active')->count(),
            'pending_logbooks' => Logbook::whereIn('assignment_id', $assignmentIds)->where('status', 'pending')->count(),
        ]);
    }

    public function fieldSupervisor(Request $request)
    {
        $fieldSupervisor = $request->user()->fieldSupervisor;
        $assignmentIds = $fieldSupervisor?->assignments()->pluck('id') ?? collect();

        return $this->successResponse('Dashboard pembimbing lapangan berhasil diambil', [
            'total_students' => $fieldSupervisor?->assignments()->distinct('student_id')->count('student_id') ?? 0,
            'pending_logbooks' => Logbook::whereIn('assignment_id', $assignmentIds)->where('status', 'pending')->count(),
            'revision_logbooks' => Logbook::whereIn('assignment_id', $assignmentIds)->where('status', 'revision')->count(),
        ]);
    }
}
