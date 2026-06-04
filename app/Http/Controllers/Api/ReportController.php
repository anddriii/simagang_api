<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InternshipAssignment;
use App\Models\Logbook;
use App\Traits\ApiResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    use ApiResponse;

    public function internshipProgress(Request $request)
    {
        $query = InternshipAssignment::query()
            ->with([
                'student.user',
                'company',
                'lecturer.user',
                'fieldSupervisor.user',
                'period',
                'logbooks',
                'warnings',
            ]);

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }

        $assignments = $query->get();

        $data = $assignments->map(function ($assignment) {
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

            return [
                'assignment_id' => $assignment->id,
                'student_name' => $assignment->student?->user?->name,
                'nim' => $assignment->student?->nim,
                'company' => $assignment->company?->name,
                'lecturer' => $assignment->lecturer?->user?->name,
                'field_supervisor' => $assignment->fieldSupervisor?->user?->name,
                'period' => $assignment->period?->name,
                'start_date' => $assignment->start_date,
                'end_date' => $assignment->end_date,
                'assignment_status' => $assignment->status,
                'total_logbooks' => $totalLogbooks,
                'approved_logbooks' => $approvedLogbooks,
                'pending_logbooks' => $pendingLogbooks,
                'revision_logbooks' => $revisionLogbooks,
                'rejected_logbooks' => $rejectedLogbooks,
                'progress_percentage' => $progressPercentage,
                'warning_status' => $activeWarning?->level ?? 'aman',
            ];
        })->values();

        return $this->successResponse('Laporan progress magang berhasil diambil', $data);
    }

    public function logbooks(Request $request)
    {
        $query = Logbook::query()
            ->with([
                'student.user',
                'assignment.company',
                'assignment.lecturer.user',
                'assignment.fieldSupervisor.user',
                'attachments',
                'validations',
            ])
            ->orderBy('activity_date');

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('assignment_id')) {
            $query->where('assignment_id', $request->assignment_id);
        }

        $logbooks = $query->get()->map(function ($logbook) {
            return [
                'id' => $logbook->id,
                'student_name' => $logbook->student?->user?->name,
                'nim' => $logbook->student?->nim,
                'company' => $logbook->assignment?->company?->name,
                'lecturer' => $logbook->assignment?->lecturer?->user?->name,
                'field_supervisor' => $logbook->assignment?->fieldSupervisor?->user?->name,
                'activity_date' => $logbook->activity_date,
                'start_time' => $logbook->start_time,
                'end_time' => $logbook->end_time,
                'title' => $logbook->title,
                'description' => $logbook->description,
                'problem' => $logbook->problem,
                'status' => $logbook->status,
                'submitted_at' => $logbook->submitted_at,
            ];
        })->values();

        return $this->successResponse('Laporan logbook berhasil diambil', $logbooks);
    }

    public function exportInternshipProgressPdf(Request $request)
    {
        $data = $this->getInternshipProgressData($request);

        $pdf = Pdf::loadView('reports.internship-progress', [
            'title' => 'Laporan Progress Magang',
            'reports' => $data,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-progress-magang.pdf');
    }

    public function exportLogbookPdf(Request $request)
    {
        $data = $this->getLogbookData($request);

        $pdf = Pdf::loadView('reports.logbooks', [
            'title' => 'Laporan Logbook Magang',
            'reports' => $data,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-logbook-magang.pdf');
    }

    public function exportInternshipProgressExcel(Request $request): StreamedResponse
    {
        $data = $this->getInternshipProgressData($request);

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Nama Mahasiswa',
                'NIM',
                'Perusahaan',
                'Dosen Pembimbing',
                'Pembimbing Lapangan',
                'Periode',
                'Total Logbook',
                'Logbook Disetujui',
                'Progress (%)',
                'Status Warning',
            ]);

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row['student_name'],
                    $row['nim'],
                    $row['company'],
                    $row['lecturer'],
                    $row['field_supervisor'],
                    $row['period'],
                    $row['total_logbooks'],
                    $row['approved_logbooks'],
                    $row['progress_percentage'],
                    $row['warning_status'],
                ]);
            }

            fclose($handle);
        }, 'laporan-progress-magang.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportLogbookExcel(Request $request): StreamedResponse
    {
        $data = $this->getLogbookData($request);

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Nama Mahasiswa',
                'NIM',
                'Perusahaan',
                'Tanggal',
                'Jam Mulai',
                'Jam Selesai',
                'Judul Kegiatan',
                'Deskripsi',
                'Kendala',
                'Status',
            ]);

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row['student_name'],
                    $row['nim'],
                    $row['company'],
                    $row['activity_date'],
                    $row['start_time'],
                    $row['end_time'],
                    $row['title'],
                    $row['description'],
                    $row['problem'],
                    $row['status'],
                ]);
            }

            fclose($handle);
        }, 'laporan-logbook-magang.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function getInternshipProgressData(Request $request)
    {
        $query = InternshipAssignment::query()
            ->with([
                'student.user',
                'company',
                'lecturer.user',
                'fieldSupervisor.user',
                'period',
                'logbooks',
                'warnings',
            ]);

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }

        return $query->get()->map(function ($assignment) {
            $logbooks = $assignment->logbooks;

            $totalLogbooks = $logbooks->count();
            $approvedLogbooks = $logbooks->where('status', 'approved')->count();

            $progressPercentage = $totalLogbooks > 0
                ? round(($approvedLogbooks / $totalLogbooks) * 100, 2)
                : 0;

            $activeWarning = $assignment->warnings
                ->where('status', 'active')
                ->sortByDesc('created_at')
                ->first();

            return [
                'assignment_id' => $assignment->id,
                'student_name' => $assignment->student?->user?->name,
                'nim' => $assignment->student?->nim,
                'company' => $assignment->company?->name,
                'lecturer' => $assignment->lecturer?->user?->name,
                'field_supervisor' => $assignment->fieldSupervisor?->user?->name,
                'period' => $assignment->period?->name,
                'total_logbooks' => $totalLogbooks,
                'approved_logbooks' => $approvedLogbooks,
                'progress_percentage' => $progressPercentage,
                'warning_status' => $activeWarning?->level ?? 'aman',
            ];
        })->values();
    }

    private function getLogbookData(Request $request)
    {
        $query = Logbook::query()
            ->with([
                'student.user',
                'assignment.company',
                'assignment.lecturer.user',
                'assignment.fieldSupervisor.user',
            ])
            ->orderBy('activity_date');

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('assignment_id')) {
            $query->where('assignment_id', $request->assignment_id);
        }

        return $query->get()->map(function ($logbook) {
            return [
                'id' => $logbook->id,
                'student_name' => $logbook->student?->user?->name,
                'nim' => $logbook->student?->nim,
                'company' => $logbook->assignment?->company?->name,
                'lecturer' => $logbook->assignment?->lecturer?->user?->name,
                'field_supervisor' => $logbook->assignment?->fieldSupervisor?->user?->name,
                'activity_date' => $logbook->activity_date,
                'start_time' => $logbook->start_time,
                'end_time' => $logbook->end_time,
                'title' => $logbook->title,
                'description' => $logbook->description,
                'problem' => $logbook->problem,
                'status' => $logbook->status,
                'submitted_at' => $logbook->submitted_at,
            ];
        })->values();
    }
}