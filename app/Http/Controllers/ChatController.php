<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsgChat;
use App\Models\MsgProfile;
use App\Models\MsgGrp;
use App\Models\MsgConversation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Exception;

class ChatController extends Controller
{
    public function index(){
        if(!session('user_id')){
            return redirect('/login');
        }else{
            $users = MsgProfile::select('*')->get();
            $chats = MsgChat::where('user_id_sender', session('user_id'))
            ->select('*')
            ->get(); 
            $msg = MsgConversation::where('convo_id', session('convo_id'))
            ->select('*')
            ->get();
            foreach ($msg as $message) {
                $message->conversation = Crypt::decryptString($message->conversation);
                $message->filePath = Crypt::decryptString($message->filePath);
            }   
            $allGroups = MsgGrp::select('grpId','grp_name', 'convo_id', 'members')->get();
            $grps = $allGroups->filter(function ($grp) {
                return in_array(session('user_id'), json_decode($grp->members, true));
            });
            $user = null;
            if(session('DisplayTable')=='pvt'){
                $user = MsgProfile::where('user_id', session('DisplayUserId'))->first();
            }
            if(session('DisplayTable')=='grp'){
                $user = MsgGrp::where('convo_id', session('convo_id'))->first();
            }
            return view('dashboard', compact('user','users', 'chats', 'msg', 'grps'));
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id_sender' => 'required|string',
                'receiver' => 'nullable|string',
                'user_id_receiver' => 'required|string',
            ]);
            $convo_id = Str::uuid();

            $chat = new MsgChat();
            $chat->user_id_sender = $validated['user_id_sender'];
            $chat->receiver = $validated['receiver'];
            $chat->user_id_receiver = $validated['user_id_receiver'];
            $chat->convo_id = $convo_id;
            $chat->save();

            $chat = new MsgChat();
            $chat->user_id_sender = $validated['user_id_receiver'];
            $chat->receiver = session('full_name');
            $chat->user_id_receiver = $validated['user_id_sender'];
            $chat->convo_id = $convo_id;
            $chat->save();

            session(['id_receiver' => $validated['user_id_receiver']]);
            session(['receivers' => $validated['receiver']]);
            session(['convo_id' => $convo_id]);

            return redirect('dashboard');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while sending the message. Please try again.');
        }
    }

    public function Grpstore(Request $request)
    {
        $request->validate([
            'grp_name' => 'required|string|max:255',
            'selected_users' => 'required|array|min:2',
        ]);

        $convoId = Str::uuid();
        $grpId = Str::uuid();
        MsgGrp::create([
            'grpId'=> $grpId,
            'grp_name' => $request->grp_name,
            'members' => json_encode($request->selected_users), 
            'convo_id' => $convoId,
        ]);
        session(['id_receiver' => $request->grp_name]);
        session(['receivers' => implode(', ', json_decode(json_encode($request->selected_users), true))]);
        session(['convo_id' => $convoId]);
        return redirect()->back();
    }

    public function delete(Request $request)
    {
    try {
        // Validate input
        $validated = $request->validate([
            'user_id_sender' => 'required|string',
            'user_id_receiver' => 'required|string',
            'convo_id' => 'required',
        ]);
        MsgChat::where(function ($query) use ($validated) {
            $query->where('user_id_sender', $validated['user_id_sender'])
                ->where('user_id_receiver', $validated['user_id_receiver']);
        })->orWhere(function ($query) use ($validated) {
            $query->where('user_id_sender', $validated['user_id_receiver'])
                ->where('user_id_receiver', $validated['user_id_sender']);
        })->delete();
        MsgConversation::where('convo_id', $validated['convo_id'])->delete();
        if(session('id_receiver') == $request->user_id_receiver){
            session()->forget('id_receiver');
            session()->forget('receivers');
            session()->forget('convo_id');
        }
        return redirect()->back()->with('success', 'Chat deleted successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while deleting the chat. Please try again.');
    }
    }

    public function Grpdelete(Request $request){
        try {
            $validated = $request->validate([
                'grpId' => 'required',
                'grp_name' => 'required|string',
                'convo_id' => 'required',
            ]);
            $group = MsgGrp::where('grpId', $validated['grpId'])->first();
            if (!$group) {
                return redirect()->back();
            }
            $members = json_decode($group->members, true);
            $members = array_filter($members, fn($member) => $member !== session('user_id'));
            if (count($members) <= 1) {
                $group->delete();
                MsgConversation::where('convo_id', $validated['convo_id'])->delete();
            } else {
                $group->update(['members' => json_encode(array_values($members))]);
            }
            session()->forget(['convo_id']);
            session()->forget('receivers');
            session()->forget(['id_receiver']);
            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while leaving the group. Please try again.');
        }
    }

    public function start(Request $request){
        if(session()->has('convo_id')){
            if(session('convo_id') == $request->convo_id){
                session()->forget('id_receiver');
                session()->forget('receivers');
                session()->forget('convo_id');
                return redirect()->back();
            }
        }
        session()->forget('DisplayTable');
        session()->forget('DisplayUserId');

        session(['id_receiver' => $request->id_receiver]);
        session(['receivers' => $request->receivers]);
        session(['convo_id' => $request->convo_id]);
        return redirect()->back();
        }

    public function newMsg(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|string',
            'message' => 'nullable|string',
            'convo_id' => 'required',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,mp4,mp3,wav,avi,mov,mkv|max:20480',
        ]);
        $msg = new MsgConversation();
        $msg->sender =  $validated['user_id'];
        $msg->convo_id =  $validated['convo_id'];
        $newFileName=null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName(); 
            $extension = $file->getClientOriginalExtension(); 
            $filenameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME); 
            $newFileName = $filenameWithoutExt . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('uploads', $newFileName, 'public');
        }
        $msg->filepath = Crypt::encryptString($newFileName);
        $msg->conversation = Crypt::encryptString($validated['message']);
        $msg->save();
        return redirect()->back();
    }

    public function deleteMsg(Request $request){
        $group = MsgConversation::find($request->id);
        $group->delete();
        return redirect()->back();
    }

    public function profileDisplay(Request $request){
        $membersArray = explode(',', $request->receivers);
        $table = (count($membersArray) > 1) ? 'grp' : 'pvt';
        $DisplayUserId = $request->id_receiver;
        session(['DisplayTable' => $table]);
        session(['DisplayUserId' => $DisplayUserId]);
        return redirect()->back();
    }

    public function profileDisplayOne(Request $request){
        session(['DisplayTable' => 'pvt']);
        session(['DisplayUserId' => $request->id_receivers]);
        return redirect()->back();
    }
    
    public function profileClose(){
        session()->forget('DisplayTable');
        session()->forget('DisplayUserId');
        return redirect()->back();
    }
}