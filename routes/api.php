<?php

use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\FieldSupervisorController;
use App\Http\Controllers\Api\InternshipApplicationController;
use App\Http\Controllers\Api\InternshipAssignmentController;
use App\Http\Controllers\Api\InternshipPeriodController;
use App\Http\Controllers\Api\KnowledgeBaseController;
use App\Http\Controllers\Api\LecturerController;
use App\Http\Controllers\Api\LogbookController;
use App\Http\Controllers\Api\LogbookValidationController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\WarningController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportController;

Route::get('/public/app-config', function () {
    return response()->json([
        'success' => true,
        'message' => 'Konfigurasi aplikasi berhasil diambil',
        'data' => [
            'app_name' => 'SIMAGANG',
            'app_version' => '1.0.0',
            'maintenance_mode' => false,
            'minimum_supported_version' => '1.0.0',
            'contact_admin' => 'admin@kampus.ac.id',
        ],
    ]);
});

Route::get('/health', fn () => response()->json([
    'status' => 'ok',
    'app' => config('app.name'),
]));

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
    });

    Route::get('/student/dashboard', [DashboardController::class, 'student'])->middleware(RoleMiddleware::class . ':student');
    Route::get('/lecturer/dashboard', [DashboardController::class, 'lecturer'])->middleware(RoleMiddleware::class . ':lecturer');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->middleware(RoleMiddleware::class . ':admin');
    Route::get('/field-supervisor/dashboard', [DashboardController::class, 'fieldSupervisor'])->middleware(RoleMiddleware::class . ':field_supervisor');

    Route::apiResource('students', StudentController::class)->middleware(RoleMiddleware::class . ':admin,lecturer');
    Route::apiResource('lecturers', LecturerController::class)->middleware(RoleMiddleware::class . ':admin');
    Route::apiResource('field-supervisors', FieldSupervisorController::class)->middleware(RoleMiddleware::class . ':admin');
    Route::get('/companies', [CompanyController::class, 'index'])
        ->middleware(RoleMiddleware::class . ':admin,student,lecturer,field_supervisor');

    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->middleware(RoleMiddleware::class . ':admin,student,lecturer,field_supervisor');

    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::post('/companies', [CompanyController::class, 'store']);
        Route::put('/companies/{company}', [CompanyController::class, 'update']);
        Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);
    });

    // Periode magang boleh dilihat oleh semua role yang butuh,
    // terutama mahasiswa untuk kebutuhan pengajuan magang.
    Route::get('/internship-periods', [InternshipPeriodController::class, 'index'])
        ->middleware(RoleMiddleware::class . ':admin,student,lecturer,field_supervisor');

    Route::get('/internship-periods/{internshipPeriod}', [InternshipPeriodController::class, 'show'])
        ->middleware(RoleMiddleware::class . ':admin,student,lecturer,field_supervisor');

    // Hanya admin/prodi yang boleh mengelola periode magang.
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::post('/internship-periods', [InternshipPeriodController::class, 'store']);
        Route::put('/internship-periods/{internshipPeriod}', [InternshipPeriodController::class, 'update']);
        Route::delete('/internship-periods/{internshipPeriod}', [InternshipPeriodController::class, 'destroy']);
    });

    Route::get('/internship-applications', [InternshipApplicationController::class, 'index']);
    Route::post('/internship-applications', [InternshipApplicationController::class, 'store'])->middleware(RoleMiddleware::class . ':student');
    Route::get('/internship-applications/{id}', [InternshipApplicationController::class, 'show']);
    Route::post('/internship-applications/{id}/documents', [InternshipApplicationController::class, 'uploadDocument'])->middleware(RoleMiddleware::class . ':student,admin');
    Route::put('/internship-applications/{id}/approve', [InternshipApplicationController::class, 'approve'])->middleware(RoleMiddleware::class . ':admin');
    Route::put('/internship-applications/{id}/reject', [InternshipApplicationController::class, 'reject'])->middleware(RoleMiddleware::class . ':admin');

    Route::get('/internship-assignments', [InternshipAssignmentController::class, 'index']);
    Route::get('/internship-assignments/{id}', [InternshipAssignmentController::class, 'show']);

    Route::apiResource('logbooks', LogbookController::class);
    Route::post('/logbooks/{id}/attachments', [LogbookController::class, 'uploadAttachment'])->middleware(RoleMiddleware::class . ':student');
    Route::post('/logbooks/{id}/submit', [LogbookController::class, 'submit'])->middleware(RoleMiddleware::class . ':student');

    Route::get('/field-supervisor/logbooks/pending', [LogbookValidationController::class, 'pending'])->middleware(RoleMiddleware::class . ':field_supervisor');
    Route::put('/logbooks/{id}/approve', [LogbookValidationController::class, 'approve'])->middleware(RoleMiddleware::class . ':field_supervisor');
    Route::put('/logbooks/{id}/revise', [LogbookValidationController::class, 'revise'])->middleware(RoleMiddleware::class . ':field_supervisor');
    Route::put('/logbooks/{id}/reject', [LogbookValidationController::class, 'reject'])->middleware(RoleMiddleware::class . ':field_supervisor');

    Route::get('/student/progress', [MonitoringController::class, 'myProgress'])
        ->middleware(RoleMiddleware::class . ':student');

    Route::get('/monitoring/students/{student_id}/progress', [MonitoringController::class, 'studentProgress'])
        ->middleware(RoleMiddleware::class . ':admin,lecturer,student');

    Route::get('/lecturer/monitoring', [MonitoringController::class, 'lecturerMonitoring'])->middleware(RoleMiddleware::class . ':lecturer,admin');

    Route::get('/warnings', [WarningController::class, 'index'])->middleware(RoleMiddleware::class . ':admin,lecturer');
    Route::post('/warnings/generate', [WarningController::class, 'generate'])->middleware(RoleMiddleware::class . ':admin');
    Route::put('/warnings/{id}/resolve', [WarningController::class, 'resolve'])->middleware(RoleMiddleware::class . ':admin,lecturer');

    Route::apiResource('consultations', ConsultationController::class)->only(['index', 'store', 'show']);
    Route::post('/consultations/{id}/reply', [ConsultationController::class, 'reply']);
    Route::put('/consultations/{id}/close', [ConsultationController::class, 'close']);

    Route::apiResource('assessments', AssessmentController::class)->only(['index', 'store', 'show', 'update']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'read']);
    Route::put('/notifications/read-all', [NotificationController::class, 'readAll']);

    Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])
            ->middleware('throttle:10,1');

        Route::get('/chatbot/history', [
            ChatbotController::class,
            'history',
        ]);

        Route::get('/chatbot/conversations/{id}', [
            ChatbotController::class,
            'conversation',
        ]);;

    Route::apiResource('/chatbot/knowledge-bases', KnowledgeBaseController::class)->middleware(RoleMiddleware::class . ':admin');

    Route::middleware(RoleMiddleware::class . ':admin,lecturer')->group(function () {
        Route::get('/reports/internship-progress', [ReportController::class, 'internshipProgress']);
        Route::get('/reports/internship-progress/export-pdf', [ReportController::class, 'exportInternshipProgressPdf']);
        Route::get('/reports/internship-progress/export-excel', [ReportController::class, 'exportInternshipProgressExcel']);
    });

    Route::get('/reports/logbooks', [ReportController::class, 'logbooks'])->middleware(RoleMiddleware::class . ':admin,lecturer,student');
    Route::get('/reports/logbooks/export-pdf', [ReportController::class, 'exportLogbookPdf'])->middleware(RoleMiddleware::class . ':admin,lecturer,student');
    Route::get('/reports/logbooks/export-excel', [ReportController::class, 'exportLogbookExcel'])->middleware(RoleMiddleware::class . ':admin,lecturer,student');

    Route::get('/documents', [DocumentController::class, 'index']);
    Route::post('/documents', [DocumentController::class, 'store']);
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
});
