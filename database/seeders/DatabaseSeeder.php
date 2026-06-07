<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\ChatbotConversation;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotMessage;
use App\Models\Company;
use App\Models\Consultation;
use App\Models\ConsultationReply;
use App\Models\Document;
use App\Models\FieldSupervisor;
use App\Models\InternshipApplication;
use App\Models\InternshipApplicationDocument;
use App\Models\InternshipAssignment;
use App\Models\InternshipPeriod;
use App\Models\Lecturer;
use App\Models\Logbook;
use App\Models\LogbookAttachment;
use App\Models\LogbookValidation;
use App\Models\Notification;
use App\Models\Student;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Helper
        |--------------------------------------------------------------------------
        */

        $baseUrl = rtrim(config('app.url'), '/');

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $admin = User::updateOrCreate(
            ['email' => 'admin@simagang.test'],
            [
                'name' => 'Admin Prodi',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '080000000001',
            ]
        );

        $studentUser1 = User::updateOrCreate(
            ['email' => 'mahasiswa@simagang.test'],
            [
                'name' => 'Andri Setiawan',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '083159514223',
            ]
        );

        $studentUser2 = User::updateOrCreate(
            ['email' => 'siti@simagang.test'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '081111111111',
            ]
        );

        $studentUser3 = User::updateOrCreate(
            ['email' => 'rizky@simagang.test'],
            [
                'name' => 'Rizky Pratama',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '082222222222',
            ]
        );

        $lecturerUser1 = User::updateOrCreate(
            ['email' => 'dosen@simagang.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'lecturer',
                'phone' => '080000000002',
            ]
        );

        $lecturerUser2 = User::updateOrCreate(
            ['email' => 'dosen2@simagang.test'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('password'),
                'role' => 'lecturer',
                'phone' => '080000000004',
            ]
        );

        $fieldSupervisorUser1 = User::updateOrCreate(
            ['email' => 'pembimbing@simagang.test'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('password'),
                'role' => 'field_supervisor',
                'phone' => '080000000003',
            ]
        );

        $fieldSupervisorUser2 = User::updateOrCreate(
            ['email' => 'pembimbing2@simagang.test'],
            [
                'name' => 'Nina Kartika',
                'password' => Hash::make('password'),
                'role' => 'field_supervisor',
                'phone' => '080000000005',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Companies
        |--------------------------------------------------------------------------
        */

        $company1 = Company::updateOrCreate(
            ['name' => 'PT Digital Nusantara'],
            [
                'address' => 'Cirebon',
                'field' => 'Software Development',
                'email' => 'hrd@digitalnusantara.test',
                'phone' => '08123456789',
                'quota' => 5,
                'status' => 'active',
            ]
        );

        $company2 = Company::updateOrCreate(
            ['name' => 'CV Teknologi Cirebon'],
            [
                'address' => 'Kota Cirebon',
                'field' => 'IT Consultant',
                'email' => 'hrd@teknologicirebon.test',
                'phone' => '08123456780',
                'quota' => 3,
                'status' => 'active',
            ]
        );

        $company3 = Company::updateOrCreate(
            ['name' => 'Dinas Kominfo Kabupaten Cirebon'],
            [
                'address' => 'Sumber, Kabupaten Cirebon',
                'field' => 'Government IT',
                'email' => 'magang@diskominfo.test',
                'phone' => '08123456781',
                'quota' => 10,
                'status' => 'active',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Profiles
        |--------------------------------------------------------------------------
        */

        $student1 = Student::updateOrCreate(
            ['user_id' => $studentUser1->id],
            [
                'nim' => '20230001',
                'study_program' => 'Rekayasa Perangkat Lunak',
                'class' => 'RPL-5A',
                'semester' => 5,
            ]
        );

        $student2 = Student::updateOrCreate(
            ['user_id' => $studentUser2->id],
            [
                'nim' => '20230002',
                'study_program' => 'Rekayasa Perangkat Lunak',
                'class' => 'RPL-5A',
                'semester' => 5,
            ]
        );

        $student3 = Student::updateOrCreate(
            ['user_id' => $studentUser3->id],
            [
                'nim' => '20230003',
                'study_program' => 'Rekayasa Perangkat Lunak',
                'class' => 'RPL-5B',
                'semester' => 5,
            ]
        );

        $lecturer1 = Lecturer::updateOrCreate(
            ['user_id' => $lecturerUser1->id],
            [
                'nidn' => '0420019001',
                'department' => 'Rekayasa Perangkat Lunak',
            ]
        );

        $lecturer2 = Lecturer::updateOrCreate(
            ['user_id' => $lecturerUser2->id],
            [
                'nidn' => '0420019002',
                'department' => 'Sistem Informasi',
            ]
        );

        $fieldSupervisor1 = FieldSupervisor::updateOrCreate(
            ['user_id' => $fieldSupervisorUser1->id],
            [
                'company_id' => $company1->id,
                'position' => 'IT Supervisor',
            ]
        );

        $fieldSupervisor2 = FieldSupervisor::updateOrCreate(
            ['user_id' => $fieldSupervisorUser2->id],
            [
                'company_id' => $company3->id,
                'position' => 'Kepala Seksi Aplikasi',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Internship Periods
        |--------------------------------------------------------------------------
        */

        $period1 = InternshipPeriod::updateOrCreate(
            ['name' => 'Magang Semester Genap 2026'],
            [
                'start_date' => '2026-07-01',
                'end_date' => '2026-09-30',
                'status' => 'active',
            ]
        );

        $period2 = InternshipPeriod::updateOrCreate(
            ['name' => 'Magang Semester Ganjil 2026'],
            [
                'start_date' => '2026-10-01',
                'end_date' => '2026-12-31',
                'status' => 'inactive',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Internship Applications
        |--------------------------------------------------------------------------
        */

        $application1 = InternshipApplication::updateOrCreate(
            [
                'student_id' => $student1->id,
                'company_id' => $company1->id,
                'period_id' => $period1->id,
            ],
            [
                'reason' => 'Saya ingin mengembangkan kemampuan backend development dan memahami alur kerja industri.',
                'status' => 'approved',
                'note' => 'Pengajuan disetujui.',
            ]
        );

        $application2 = InternshipApplication::updateOrCreate(
            [
                'student_id' => $student2->id,
                'company_id' => $company3->id,
                'period_id' => $period1->id,
            ],
            [
                'reason' => 'Saya ingin belajar pengelolaan sistem informasi pemerintahan dan data publik.',
                'status' => 'pending',
                'note' => null,
            ]
        );

        $application3 = InternshipApplication::updateOrCreate(
            [
                'student_id' => $student3->id,
                'company_id' => $company2->id,
                'period_id' => $period1->id,
            ],
            [
                'reason' => 'Saya ingin memperdalam kemampuan frontend dan UI/UX.',
                'status' => 'rejected',
                'note' => 'Dokumen pengajuan belum lengkap.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Internship Application Documents
        |--------------------------------------------------------------------------
        */

        InternshipApplicationDocument::updateOrCreate(
            [
                'application_id' => $application1->id,
                'type' => 'surat_pengantar',
            ],
            [
                'file_name' => 'surat_pengantar_andri.pdf',
                'file_path' => 'internship-applications/surat_pengantar_andri.pdf',
                'file_url' => $baseUrl . '/storage/internship-applications/surat_pengantar_andri.pdf',
            ]
        );

        InternshipApplicationDocument::updateOrCreate(
            [
                'application_id' => $application2->id,
                'type' => 'surat_pengantar',
            ],
            [
                'file_name' => 'surat_pengantar_siti.pdf',
                'file_path' => 'internship-applications/surat_pengantar_siti.pdf',
                'file_url' => $baseUrl . '/storage/internship-applications/surat_pengantar_siti.pdf',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Internship Assignments
        |--------------------------------------------------------------------------
        */

        $assignment1 = InternshipAssignment::updateOrCreate(
            [
                'student_id' => $student1->id,
                'period_id' => $period1->id,
            ],
            [
                'company_id' => $company1->id,
                'lecturer_id' => $lecturer1->id,
                'field_supervisor_id' => $fieldSupervisor1->id,
                'start_date' => '2026-07-01',
                'end_date' => '2026-09-30',
                'status' => 'active',
            ]
        );

        $assignment2 = InternshipAssignment::updateOrCreate(
            [
                'student_id' => $student2->id,
                'period_id' => $period1->id,
            ],
            [
                'company_id' => $company3->id,
                'lecturer_id' => $lecturer2->id,
                'field_supervisor_id' => $fieldSupervisor2->id,
                'start_date' => '2026-07-01',
                'end_date' => '2026-09-30',
                'status' => 'active',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Logbooks
        |--------------------------------------------------------------------------
        */

        $logbook1 = Logbook::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'student_id' => $student1->id,
                'activity_date' => '2026-07-01',
            ],
            [
                'start_time' => '08:00',
                'end_time' => '16:00',
                'title' => 'Pengenalan Lingkungan Kerja',
                'description' => 'Mengikuti orientasi perusahaan dan memahami alur kerja tim IT.',
                'problem' => null,
                'status' => 'approved',
                'submitted_at' => now()->subDays(4),
            ]
        );

        $logbook2 = Logbook::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'student_id' => $student1->id,
                'activity_date' => '2026-07-02',
            ],
            [
                'start_time' => '08:00',
                'end_time' => '16:00',
                'title' => 'Mempelajari Struktur Project Laravel',
                'description' => 'Mempelajari struktur folder, routing, controller, model, dan middleware pada Laravel.',
                'problem' => 'Masih perlu memahami alur middleware lebih dalam.',
                'status' => 'revision',
                'submitted_at' => now()->subDays(3),
            ]
        );

        $logbook3 = Logbook::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'student_id' => $student1->id,
                'activity_date' => '2026-07-03',
            ],
            [
                'start_time' => '08:00',
                'end_time' => '16:00',
                'title' => 'Membuat Endpoint API',
                'description' => 'Membantu membuat endpoint sederhana untuk fitur internal perusahaan.',
                'problem' => null,
                'status' => 'pending',
                'submitted_at' => now()->subDays(2),
            ]
        );

        $logbook4 = Logbook::updateOrCreate(
            [
                'assignment_id' => $assignment2->id,
                'student_id' => $student2->id,
                'activity_date' => '2026-07-01',
            ],
            [
                'start_time' => '08:00',
                'end_time' => '15:30',
                'title' => 'Pengenalan Sistem Informasi Pemerintahan',
                'description' => 'Mempelajari alur pengelolaan data dan dokumen pada instansi pemerintahan.',
                'problem' => null,
                'status' => 'approved',
                'submitted_at' => now()->subDays(4),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Logbook Attachments
        |--------------------------------------------------------------------------
        */

        LogbookAttachment::updateOrCreate(
            ['logbook_id' => $logbook1->id],
            [
                'file_name' => 'bukti_kegiatan_1.jpg',
                'file_path' => 'logbooks/bukti_kegiatan_1.jpg',
                'file_url' => $baseUrl . '/storage/logbooks/bukti_kegiatan_1.jpg',
            ]
        );

        LogbookAttachment::updateOrCreate(
            ['logbook_id' => $logbook3->id],
            [
                'file_name' => 'bukti_kegiatan_3.jpg',
                'file_path' => 'logbooks/bukti_kegiatan_3.jpg',
                'file_url' => $baseUrl . '/storage/logbooks/bukti_kegiatan_3.jpg',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Logbook Validations
        |--------------------------------------------------------------------------
        */

        LogbookValidation::updateOrCreate(
            ['logbook_id' => $logbook1->id],
            [
                'field_supervisor_id' => $fieldSupervisor1->id,
                'status' => 'approved',
                'note' => 'Kegiatan sudah sesuai dengan aktivitas magang.',
                'validated_at' => now()->subDays(3),
            ]
        );

        LogbookValidation::updateOrCreate(
            ['logbook_id' => $logbook2->id],
            [
                'field_supervisor_id' => $fieldSupervisor1->id,
                'status' => 'revision',
                'note' => 'Deskripsi kegiatan perlu dibuat lebih detail.',
                'validated_at' => now()->subDays(2),
            ]
        );

        LogbookValidation::updateOrCreate(
            ['logbook_id' => $logbook4->id],
            [
                'field_supervisor_id' => $fieldSupervisor2->id,
                'status' => 'approved',
                'note' => 'Logbook sudah sesuai.',
                'validated_at' => now()->subDays(3),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Warnings
        |--------------------------------------------------------------------------
        */

        Warning::updateOrCreate(
            [
                'student_id' => $student1->id,
                'assignment_id' => $assignment1->id,
                'warning_type' => 'logbook_revision',
            ],
            [
                'level' => 'medium',
                'message' => 'Mahasiswa memiliki logbook dengan status revisi yang perlu segera diperbaiki.',
                'status' => 'active',
                'resolved_note' => null,
                'resolved_at' => null,
            ]
        );

        Warning::updateOrCreate(
            [
                'student_id' => $student2->id,
                'assignment_id' => $assignment2->id,
                'warning_type' => 'progress_low',
            ],
            [
                'level' => 'low',
                'message' => 'Progress logbook mahasiswa masih perlu dipantau.',
                'status' => 'resolved',
                'resolved_note' => 'Mahasiswa sudah diberikan arahan oleh dosen pembimbing.',
                'resolved_at' => now()->subDay(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Consultations
        |--------------------------------------------------------------------------
        */

        $consultation1 = Consultation::updateOrCreate(
            [
                'student_id' => $student1->id,
                'lecturer_id' => $lecturer1->id,
                'subject' => 'Kendala validasi logbook',
            ],
            [
                'message' => 'Pembimbing lapangan meminta revisi pada logbook tanggal 2026-07-02.',
                'status' => 'open',
            ]
        );

        ConsultationReply::updateOrCreate(
            [
                'consultation_id' => $consultation1->id,
                'sender_id' => $studentUser1->id,
                'message' => 'Pak, saya belum memahami bagian yang harus diperbaiki.',
            ],
            [
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        );

        ConsultationReply::updateOrCreate(
            [
                'consultation_id' => $consultation1->id,
                'sender_id' => $lecturerUser1->id,
                'message' => 'Silakan perjelas deskripsi aktivitas dan hasil pekerjaan yang dilakukan pada hari tersebut.',
            ],
            [
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Assessments
        |--------------------------------------------------------------------------
        */

        Assessment::updateOrCreate(
            ['assignment_id' => $assignment1->id],
            [
                'field_supervisor_id' => $fieldSupervisor1->id,
                'discipline_score' => 90,
                'communication_score' => 85,
                'technical_score' => 88,
                'responsibility_score' => 92,
                'adaptability_score' => 86,
                'initiative_score' => 87,
                'average_score' => 88,
                'final_note' => 'Mahasiswa menunjukkan kinerja yang baik selama magang.',
            ]
        );

        Assessment::updateOrCreate(
            ['assignment_id' => $assignment2->id],
            [
                'field_supervisor_id' => $fieldSupervisor2->id,
                'discipline_score' => 86,
                'communication_score' => 84,
                'technical_score' => 82,
                'responsibility_score' => 88,
                'adaptability_score' => 85,
                'initiative_score' => 83,
                'average_score' => 85,
                'final_note' => 'Mahasiswa cukup aktif dan mampu mengikuti arahan pembimbing.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Documents / Final Reports
        |--------------------------------------------------------------------------
        */

        Document::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'type' => 'laporan_akhir',
            ],
            [
                'user_id' => $studentUser1->id,
                'file_name' => 'laporan_akhir_andri.pdf',
                'file_path' => 'documents/laporan_akhir_andri.pdf',
                'file_url' => $baseUrl . '/storage/documents/laporan_akhir_andri.pdf',
            ]
        );

        Document::updateOrCreate(
            [
                'assignment_id' => $assignment2->id,
                'type' => 'laporan_akhir',
            ],
            [
                'user_id' => $studentUser2->id,
                'file_name' => 'laporan_akhir_siti.pdf',
                'file_path' => 'documents/laporan_akhir_siti.pdf',
                'file_url' => $baseUrl . '/storage/documents/laporan_akhir_siti.pdf',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Notifications
        |--------------------------------------------------------------------------
        */

        Notification::updateOrCreate(
            [
                'user_id' => $studentUser1->id,
                'type' => 'application_approved',
                'title' => 'Pengajuan Magang Disetujui',
            ],
            [
                'message' => 'Pengajuan magang Anda di PT Digital Nusantara telah disetujui.',
                'is_read' => false,
            ]
        );

        Notification::updateOrCreate(
            [
                'user_id' => $studentUser1->id,
                'type' => 'logbook_revision',
                'title' => 'Logbook Perlu Revisi',
            ],
            [
                'message' => 'Logbook tanggal 2026-07-02 perlu diperbaiki sesuai catatan pembimbing lapangan.',
                'is_read' => false,
            ]
        );

        Notification::updateOrCreate(
            [
                'user_id' => $fieldSupervisorUser1->id,
                'type' => 'logbook_pending',
                'title' => 'Logbook Menunggu Validasi',
            ],
            [
                'message' => 'Terdapat logbook mahasiswa yang menunggu validasi.',
                'is_read' => false,
            ]
        );

        Notification::updateOrCreate(
            [
                'user_id' => $lecturerUser1->id,
                'type' => 'consultation_reply',
                'title' => 'Konsultasi Mahasiswa',
            ],
            [
                'message' => 'Mahasiswa bimbingan mengajukan konsultasi terkait validasi logbook.',
                'is_read' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Chatbot Knowledge Base
        |--------------------------------------------------------------------------
        */

        ChatbotKnowledgeBase::updateOrCreate(
            ['title' => 'Cara Mengisi Logbook'],
            [
                'category' => 'logbook',
                'content' => 'Mahasiswa wajib mengisi logbook harian melalui menu Logbook. Isi tanggal kegiatan, jam mulai, jam selesai, judul kegiatan, deskripsi kegiatan, kendala jika ada, lalu submit untuk divalidasi oleh pembimbing lapangan.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );

        ChatbotKnowledgeBase::updateOrCreate(
            ['title' => 'Validasi Logbook'],
            [
                'category' => 'validasi',
                'content' => 'Logbook yang telah dikirim mahasiswa akan divalidasi oleh pembimbing lapangan. Status validasi dapat berupa pending, approved, revision, atau rejected.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );

        ChatbotKnowledgeBase::updateOrCreate(
            ['title' => 'Dokumen Magang'],
            [
                'category' => 'dokumen',
                'content' => 'Dokumen magang yang umum digunakan meliputi surat pengantar, dokumen pendukung, logbook, dan laporan akhir magang.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );

        ChatbotKnowledgeBase::updateOrCreate(
            ['title' => 'Alur Pengajuan Magang'],
            [
                'category' => 'pengajuan',
                'content' => 'Mahasiswa memilih perusahaan dan periode magang, mengisi alasan pengajuan, mengupload dokumen pendukung, lalu menunggu review dari admin/prodi.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Chatbot Conversations & Messages
        |--------------------------------------------------------------------------
        */

        $conversation = ChatbotConversation::updateOrCreate(
            [
                'user_id' => $studentUser1->id,
                'title' => 'Pertanyaan Logbook',
            ],
            []
        );

        ChatbotMessage::updateOrCreate(
            [
                'conversation_id' => $conversation->id,
                'sender' => 'user',
                'message' => 'Bagaimana cara mengisi logbook magang?',
            ],
            [
                'response' => null,
                'source' => null,
            ]
        );

        ChatbotMessage::updateOrCreate(
            [
                'conversation_id' => $conversation->id,
                'sender' => 'bot',
                'message' => 'Untuk mengisi logbook, buka menu Logbook, pilih tambah logbook, isi tanggal, jam kegiatan, judul, deskripsi, kendala jika ada, lalu submit untuk validasi.',
            ],
            [
                'response' => 'Untuk mengisi logbook, buka menu Logbook, pilih tambah logbook, isi tanggal, jam kegiatan, judul, deskripsi, kendala jika ada, lalu submit untuk validasi.',
                'source' => 'knowledge_base',
            ]
        );
    }
}