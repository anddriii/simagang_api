<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class LecturerController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=Lecturer::with('user')->when($request->search, fn($q,$s)=>$q->where('nidn','like',"%$s%")->orWhereHas('user',fn($u)=>$u->where('name','like',"%$s%")));return $this->paginatedResponse('Data dosen berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));}
    public function store(Request $request){$data=$request->validate(['name'=>'required|string','email'=>'required|email|unique:users,email','password'=>'nullable|string|min:8','nidn'=>'nullable|string|unique:lecturers,nidn','department'=>'nullable|string','phone'=>'nullable|string']);$user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password']??'password'),'role'=>'lecturer','phone'=>$data['phone']??null]);$lecturer=Lecturer::create(['user_id'=>$user->id,'nidn'=>$data['nidn']??null,'department'=>$data['department']??null]);return $this->successResponse('Data dosen berhasil ditambahkan',$lecturer->load('user'),201);}
    public function show(Lecturer $lecturer){return $this->successResponse('Detail dosen berhasil diambil',$lecturer->load('user'));}
    public function update(Request $request, Lecturer $lecturer){$data=$request->validate(['name'=>'required|string','email'=>'required|email|unique:users,email,'.$lecturer->user_id,'nidn'=>'nullable|string|unique:lecturers,nidn,'.$lecturer->id,'department'=>'nullable|string','phone'=>'nullable|string']);$lecturer->user->update(['name'=>$data['name'],'email'=>$data['email'],'phone'=>$data['phone']??null]);$lecturer->update(['nidn'=>$data['nidn']??null,'department'=>$data['department']??null]);return $this->successResponse('Data dosen berhasil diperbarui',$lecturer->load('user'));}
    public function destroy(Lecturer $lecturer){$lecturer->user()->delete();return $this->successResponse('Data dosen berhasil dihapus');}
}
