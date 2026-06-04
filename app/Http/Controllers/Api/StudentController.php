<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class StudentController extends Controller
{
    use ApiResponse;
    public function index(Request $request){
        $q = Student::with('user')->when($request->search, fn($q,$s)=>$q->where('nim','like',"%$s%")->orWhereHas('user',fn($u)=>$u->where('name','like',"%$s%")));
        return $this->paginatedResponse('Data mahasiswa berhasil diambil', $q->latest()->paginate($request->integer('per_page',10)));
    }
    public function store(Request $request){
        $data=$request->validate(['name'=>'required|string|max:255','email'=>'required|email|unique:users,email','password'=>'nullable|string|min:8','nim'=>'required|string|unique:students,nim','study_program'=>'required|string','class'=>'nullable|string','semester'=>'required|integer|min:1','phone'=>'nullable|string']);
        $user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password']??'password'),'role'=>'student','phone'=>$data['phone']??null]);
        $student=Student::create(['user_id'=>$user->id,'nim'=>$data['nim'],'study_program'=>$data['study_program'],'class'=>$data['class']??null,'semester'=>$data['semester']]);
        return $this->successResponse('Data mahasiswa berhasil ditambahkan',$student->load('user'),201);
    }
    public function show(Student $student){ return $this->successResponse('Detail mahasiswa berhasil diambil',$student->load('user')); }
    public function update(Request $request, Student $student){
        $data=$request->validate(['name'=>'required|string|max:255','email'=>'required|email|unique:users,email,'.$student->user_id,'nim'=>'required|string|unique:students,nim,'.$student->id,'study_program'=>'required|string','class'=>'nullable|string','semester'=>'required|integer|min:1','phone'=>'nullable|string']);
        $student->user->update(['name'=>$data['name'],'email'=>$data['email'],'phone'=>$data['phone']??null]);
        $student->update(['nim'=>$data['nim'],'study_program'=>$data['study_program'],'class'=>$data['class']??null,'semester'=>$data['semester']]);
        return $this->successResponse('Data mahasiswa berhasil diperbarui',$student->load('user'));
    }
    public function destroy(Student $student){ $student->user()->delete(); return $this->successResponse('Data mahasiswa berhasil dihapus'); }
}
