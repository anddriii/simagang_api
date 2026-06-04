<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ChatbotKnowledgeBase;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
class KnowledgeBaseController extends Controller
{
    use ApiResponse;
    public function index(Request $request){$q=ChatbotKnowledgeBase::query()->when($request->search,fn($q,$s)=>$q->where('title','like',"%$s%")->orWhere('content','like',"%$s%"));return $this->paginatedResponse('Knowledge base berhasil diambil',$q->latest()->paginate($request->integer('per_page',10)));}
    public function store(Request $request){$data=$request->validate(['title'=>'required|string','category'=>'nullable|string','content'=>'required|string','status'=>'required|in:active,inactive']);$kb=ChatbotKnowledgeBase::create([...$data,'created_by'=>$request->user()->id]);return $this->successResponse('Knowledge base berhasil ditambahkan',$kb,201);}
    public function show($id){return $this->successResponse('Detail knowledge base berhasil diambil',ChatbotKnowledgeBase::findOrFail($id));}
    public function update(Request $request,$id){$kb=ChatbotKnowledgeBase::findOrFail($id);$data=$request->validate(['title'=>'required|string','category'=>'nullable|string','content'=>'required|string','status'=>'required|in:active,inactive']);$kb->update($data);return $this->successResponse('Knowledge base berhasil diperbarui',$kb);}
    public function destroy($id){ChatbotKnowledgeBase::findOrFail($id)->delete();return $this->successResponse('Knowledge base berhasil dihapus');}
}
