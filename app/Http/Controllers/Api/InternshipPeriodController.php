<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\InternshipPeriod;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class InternshipPeriodController extends Controller
{
    use ApiResponse;
    public function index(Request $request){return $this->paginatedResponse('Data periode magang berhasil diambil',InternshipPeriod::latest()->paginate($request->integer('per_page',10)));}
    public function store(Request $request){$data=$request->validate(['name'=>'required|string','start_date'=>'required|date','end_date'=>'required|date|after_or_equal:start_date','status'=>'required|in:active,inactive']);$period=InternshipPeriod::create($data);return $this->successResponse('Periode magang berhasil ditambahkan',$period,201);}
    public function show(InternshipPeriod $internshipPeriod){return $this->successResponse('Detail periode magang berhasil diambil',$internshipPeriod);}
    public function update(Request $request, InternshipPeriod $internshipPeriod){$data=$request->validate(['name'=>'required|string','start_date'=>'required|date','end_date'=>'required|date|after_or_equal:start_date','status'=>'required|in:active,inactive']);$internshipPeriod->update($data);return $this->successResponse('Periode magang berhasil diperbarui',$internshipPeriod);}
    public function destroy(InternshipPeriod $internshipPeriod){$internshipPeriod->delete();return $this->successResponse('Periode magang berhasil dihapus');}
}
