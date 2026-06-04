<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class CompanyController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=Company::query()->when($request->search,fn($q,$s)=>$q->where('name','like',"%$s%")->orWhere('field','like',"%$s%"));return $this->paginatedResponse('Data perusahaan berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));}
    public function store(Request $request){$data=$request->validate(['name'=>'required|string','address'=>'nullable|string','field'=>'nullable|string','email'=>'nullable|email','phone'=>'nullable|string','quota'=>'nullable|integer|min:0','status'=>'nullable|in:active,inactive']);$company=Company::create($data);return $this->successResponse('Data perusahaan berhasil ditambahkan',$company,201);}
    public function show(Company $company){return $this->successResponse('Detail perusahaan berhasil diambil',$company->load('fieldSupervisors.user'));}
    public function update(Request $request, Company $company){$data=$request->validate(['name'=>'required|string','address'=>'nullable|string','field'=>'nullable|string','email'=>'nullable|email','phone'=>'nullable|string','quota'=>'nullable|integer|min:0','status'=>'nullable|in:active,inactive']);$company->update($data);return $this->successResponse('Data perusahaan berhasil diperbarui',$company);}
    public function destroy(Company $company){$company->delete();return $this->successResponse('Data perusahaan berhasil dihapus');}
}
