<?php

namespace Database\Seeders;

use App\Models\ChatbotKnowledgeBase;
use App\Models\Company;
use App\Models\FieldSupervisor;
use App\Models\InternshipPeriod;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@simagang.test'],
            ['name' => 'Admin Prodi', 'password' => Hash::make('password'), 'role' => 'admin', 'phone' => '080000000001']
        );

        $studentUser = User::firstOrCreate(
            ['email' => 'mahasiswa@simagang.test'],
            ['name' => 'Andri Setiawan', 'password' => Hash::make('password'), 'role' => 'student', 'phone' => '083159514223']
        );

        $lecturerUser = User::firstOrCreate(
            ['email' => 'dosen@simagang.test'],
            ['name' => 'Budi Santoso', 'password' => Hash::make('password'), 'role' => 'lecturer', 'phone' => '080000000002']
        );

        $fieldSupervisorUser = User::firstOrCreate(
            ['email' => 'pembimbing@simagang.test'],
            ['name' => 'Ahmad Fauzi', 'password' => Hash::make('password'), 'role' => 'field_supervisor', 'phone' => '080000000003']
        );

        $company = Company::firstOrCreate(
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

        Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            ['nim' => '20230001', 'study_program' => 'Rekayasa Perangkat Lunak', 'class' => 'RPL-5A', 'semester' => 5]
        );

        Lecturer::firstOrCreate(
            ['user_id' => $lecturerUser->id],
            ['nidn' => '0420019001', 'department' => 'Rekayasa Perangkat Lunak']
        );

        FieldSupervisor::firstOrCreate(
            ['user_id' => $fieldSupervisorUser->id],
            ['company_id' => $company->id, 'position' => 'IT Supervisor']
        );

        InternshipPeriod::firstOrCreate(
            ['name' => 'Magang Semester Genap 2026'],
            ['start_date' => '2026-07-01', 'end_date' => '2026-09-30', 'status' => 'active']
        );

        ChatbotKnowledgeBase::firstOrCreate(
            ['title' => 'Cara Mengisi Logbook'],
            [
                'category' => 'logbook',
                'content' => 'Mahasiswa wajib mengisi logbook harian melalui menu Logbook. Isi tanggal kegiatan, jam mulai, jam selesai, judul kegiatan, deskripsi kegiatan, kendala jika ada, lalu submit untuk divalidasi oleh pembimbing lapangan.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );

        ChatbotKnowledgeBase::firstOrCreate(
            ['title' => 'Validasi Logbook'],
            [
                'category' => 'validasi',
                'content' => 'Logbook yang telah dikirim mahasiswa akan divalidasi oleh pembimbing lapangan. Status validasi dapat berupa pending, approved, revision, atau rejected.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );
    }
}
