<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CloudApi;
use App\Traits\Notifications;
use Auth;
use Http;
class CloudApiController extends Controller
{
    use Notifications;

    public function __construct(){
         $this->middleware('permission:cloudapi'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cloudapis = CloudApi::query();

        if (!empty($request->search)) {
            if ($request->type == 'email') {
                $cloudapis = $cloudapis->whereHas('user',function($q) use ($request){
                    return $q->where('email',$request->search);
                });
            }
            else{
                $cloudapis = $cloudapis->where($request->type,'LIKE','%'.$request->search.'%');
            }
        }

        $cloudapis = $cloudapis->withCount('smstransaction')->with('user')->latest()->paginate(30);
        $type = $request->type ?? '';

        $totalCloudApis= CloudApi::count();
        $totalActiveCloudApis= CloudApi::where('status',1)->count();
        $totalInactiveCloudApis= CloudApi::where('status',0)->count();

        return view('admin.cloudapis.index',compact('cloudapis','request','type','totalCloudApis','totalActiveCloudApis','totalInactiveCloudApis'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cloudapi = CloudApi::findorFail($id);
        $cloudapi->delete();

        $title = 'Your a cloudapi was removed by admin';
        $notification['user_id'] = $cloudapi->user_id;
        $notification['title']   = $title;
        $notification['url'] = '/user/cloudapi';

        $this->createNotification($notification);

        return response()->json([
            'redirect' => route('admin.cloudapis.index'),
            'message'  => __('CloudApi Removed successfully.')
        ]);
    }
}
