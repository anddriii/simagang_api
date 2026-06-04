<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ChatbotController extends Controller
{
    use ApiResponse;
    public function ask(Request $request){
        $data=$request->validate(['message'=>'required|string|max:1000','conversation_id'=>'nullable|exists:chatbot_conversations,id']);
        $conversation=isset($data['conversation_id'])?ChatbotConversation::where('user_id',$request->user()->id)->find($data['conversation_id']):null;
        if(!$conversation){$conversation=ChatbotConversation::create(['user_id'=>$request->user()->id,'title'=>Str::limit($data['message'],50)]);} 
        $answer=$this->findAnswer($data['message']);
        ChatbotMessage::create(['conversation_id'=>$conversation->id,'sender'=>'user','message'=>$data['message'],'response'=>$answer['response'],'source'=>$answer['source']]);
        return $this->successResponse('Chatbot berhasil menjawab pertanyaan',['conversation_id'=>$conversation->id,'user_message'=>$data['message'],'bot_response'=>$answer['response'],'source'=>$answer['source'],'created_at'=>now()]);
    }
    private function findAnswer(string $message): array{
        $keywords=collect(preg_split('/\s+/', strtolower($message)))->filter(fn($w)=>strlen($w)>3)->values();
        $kb=ChatbotKnowledgeBase::where('status','active')->get()->sortByDesc(function($row)use($keywords){$text=strtolower($row->title.' '.$row->category.' '.$row->content);return $keywords->filter(fn($k)=>str_contains($text,$k))->count();})->first();
        if($kb){return ['response'=>$kb->content,'source'=>'knowledge_base'];}
        return ['response'=>'Maaf, saya belum menemukan jawaban yang sesuai. Silakan hubungi admin/prodi atau dosen pembimbing untuk informasi lebih lanjut.','source'=>'fallback'];
    }
    public function history(Request $request){$q=ChatbotConversation::where('user_id',$request->user()->id)->with('messages')->latest();return $this->paginatedResponse('Riwayat chatbot berhasil diambil',$q->paginate($request->integer('per_page',10)));}
    public function conversation(Request $request,$id){$c=ChatbotConversation::where('user_id',$request->user()->id)->with('messages')->findOrFail($id);return $this->successResponse('Detail percakapan berhasil diambil',$c);}
}
