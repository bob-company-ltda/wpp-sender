<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reply;
use App\Models\Template;
use App\Models\CloudApi;
use App\Models\Group;
use App\Models\Groupcontact;
use App\Traits\Cloud;
use Auth;
use DB;
class ChatbotController extends Controller
{
    use Cloud;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $replies=Reply::where('user_id',Auth::id())->with('cloudapi')->latest()->paginate(20);
        $total_replies=Reply::where('user_id',Auth::id())->count();
        $template_replies=Reply::where('user_id',Auth::id())->where('template_id','!=',null)->count();
        $text_replies=Reply::where('user_id',Auth::id())->where('template_id',null)->count();
        $cloudapis = CloudApi::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $groups = Group::where('user_id',Auth::id())->latest()->get();

        return view('user.chatbot.index',compact('templates','replies','total_replies','template_replies','text_replies','cloudapis','groups'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if (getUserPlanData('chatbot') == false) {
            return response()->json([
                'message'=>__('Chatbot features is not available with your subscription')
            ],401);  
        }

        $validated = $request->validate([
            'keyword' => 'required|max:100',
            'match_type' => 'required',
            'cloudapi' => 'required'
        ]);
        
        if($request->header_image){
        $imageLink = $this->saveFile($request, 'header_image');
        }

        $cloudapi = CloudApi::where('user_id',Auth::id())->findorFail($request->cloudapi);

        if ($request->reply_type == 'template') {
             $validated = $request->validate([
                'template' => 'required',
            ]);
            $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template);
        }
        else{
            $validated = $request->validate([
                'reply' => 'required|max:1000',
            ]);
        }
        
        $reply = new Reply;
        $reply->user_id = Auth::id();
        $reply->cloudapi_id = $request->cloudapi;
        $reply->api_key = $request->api_key ?? null;
        $reply->template_id = $request->reply_type == 'template' ? $template->id : null;
        $reply->keyword = $request->keyword;
        $reply->reply = $request->reply_type != 'template' ? $request->reply : null;
        if($request->reply_type == 'ChatGPT'){
        $reply->reply_type= $request->reply_type;
        $reply->match_type= $request->match_type == 'equal' ? 'equal' : 'like';
        }
        else{
        $reply->match_type= $request->match_type == 'equal' ? 'equal' : 'like';
        $reply->reply_type= $request->reply_type == 'template' ? 'template' : 'text';
        }
        $parameters = [
            'header_parameters' => $request->header_param !== null ? $request->header_param : ($imageLink ?? null),
            'message_parameters' => $request->body_param ?? null,
        ];
        $parametersJson = json_encode($parameters);
        $reply->parameters = $parametersJson;
        $reply->save();

       return response()->json([
                'message'  => __('Reply Created Successfully'),
                'redirect' => route('user.chatbot.index')
            ], 200);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'keyword' => 'required|max:100',
            'match_type' => 'required',
            'reply_type' => 'required',
            'cloudapi' => 'required'
        ]);
        if($request->header_image){
        $imageLink = $this->saveFile($request, 'header_image');
        }

        $cloudapi = CloudApi::where('user_id',Auth::id())->findorFail($request->cloudapi);

        if ($request->reply_type == 'template') {
             $validated = $request->validate([
                'template' => 'required',
            ]);
            $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template);
        }
        else{
            $validated = $request->validate([
                'reply' => 'required|max:1000',
            ]);
        }

        $reply =  Reply::where('user_id',Auth::id())->findorFail($id);
        $reply->user_id = Auth::id();
        $reply->cloudapi_id = $request->cloudapi;
        $reply->template_id = $request->reply_type == 'template' ? $template->id : null;
        $reply->keyword = $request->keyword;
        $reply->reply = $request->reply_type != 'template' ? $request->reply : null;
        $reply->match_type= $request->match_type == 'equal' ? 'equal' : 'like';
        $reply->reply_type= $request->reply_type == 'template' ? 'template' : 'text';

        $parameters = [
            'header_parameters' => $request->header_param !== null ? $request->header_param : ($imageLink ?? null),
            'message_parameters' => $request->body_param,
        ];
        
        // Encode the combined array as JSON
        $parametersJson = json_encode($parameters);
        
        // Save the JSON data in the parameters column
        $reply->parameters = $parametersJson;
        $reply->save();

        return response()->json([
                'message'  => __('Reply Created Successfully'),
                'redirect' => route('user.chatbot.index')
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reply =  Reply::where('user_id',Auth::id())->findorFail($id);
        $reply->delete();

        return response()->json([
            'message' => __('Congratulations! Your Reply Successfully Removed'),
            'redirect' => route('user.chatbot.index')
        ]);
    }
}
