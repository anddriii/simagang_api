<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\FieldSupervisor;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class FieldSupervisorController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=FieldSupervisor::with(['user','company'])->when($request->search,fn($q,$s)=>$q->whereHas('user',fn($u)=>$u->where('name','like',"%$s%")));return $this->paginatedResponse('Data pembimbing lapangan berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));}
    public function store(Request $request){$data=$request->validate(['name'=>'required|string','email'=>'required|email|unique:users,email','password'=>'nullable|string|min:8','phone'=>'nullable|string','company_id'=>'required|exists:companies,id','position'=>'nullable|string']);$user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password']??'password'),'role'=>'field_supervisor','phone'=>$data['phone']??null]);$fs=FieldSupervisor::create(['user_id'=>$user->id,'company_id'=>$data['company_id'],'position'=>$data['position']??null]);return $this->successResponse('Data pembimbing lapangan berhasil ditambahkan',$fs->load(['user','company']),201);}
    public function show(FieldSupervisor $fieldSupervisor){return $this->successResponse('Detail pembimbing lapangan berhasil diambil',$fieldSupervisor->load(['user','company']));}
    public function update(Request $request, FieldSupervisor $fieldSupervisor){$data=$request->validate(['name'=>'required|string','email'=>'required|email|unique:users,email,'.$fieldSupervisor->user_id,'phone'=>'nullable|string','company_id'=>'required|exists:companies,id','position'=>'nullable|string']);$fieldSupervisor->user->update(['name'=>$data['name'],'email'=>$data['email'],'phone'=>$data['phone']??null]);$fieldSupervisor->update(['company_id'=>$data['company_id'],'position'=>$data['position']??null]);return $this->successResponse('Data pembimbing lapangan berhasil diperbarui',$fieldSupervisor->load(['user','company']));}
    public function destroy(FieldSupervisor $fieldSupervisor){$fieldSupervisor->user()->delete();return $this->successResponse('Data pembimbing lapangan berhasil dihapus');}
}
