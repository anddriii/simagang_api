<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class DocumentController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=Document::with(['user','assignment.company']);if($request->assignment_id){$q->where('assignment_id',$request->assignment_id);}return $this->paginatedResponse('Dokumen berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));}
    public function store(Request $request){$data=$request->validate(['file'=>'required|file|max:10240','type'=>'required|string','assignment_id'=>'nullable|exists:internship_assignments,id']);$file=$data['file'];$path=$file->store('documents','public');$doc=Document::create(['assignment_id'=>$data['assignment_id']??null,'user_id'=>$request->user()->id,'type'=>$data['type'],'file_name'=>$file->getClientOriginalName(),'file_path'=>$path,'file_url'=>Storage::url($path)]);return $this->successResponse('Dokumen berhasil diupload',$doc,201);}
    public function destroy($id){$doc=Document::findOrFail($id);if($doc->file_path){Storage::disk('public')->delete($doc->file_path);} $doc->delete();return $this->successResponse('Dokumen berhasil dihapus');}
}
