<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Traits\Uploader;
//use Cache;

class SectionController extends Controller
{
    use Uploader;

    /**
     * Display the banner section.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = get_option('languages',true);

       $banner=  get_option('banner',true);
       $features = get_option('features',true);
       $about = get_option('about_section',true);
       $overview = get_option('overview',true);
       $work = get_option('work',true);
       $download = get_option('download',true);


        return view('admin.section.index', compact('banner', 'languages', 'features','about', 'overview','work','download'));
    }

    /**
     * Store a newly created banner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->type == 'banner') {
            $data = $request->validate([
                'phone_image_1'  => ['image','max:2048'],
                'phone_image_2'=> ['image','max:2048'],
                'phone_image_3'=>['image','max:2048'],
            ]);
            $banner= Option::where('key', 'banner')->where('lang',$request->lang)->first();

            if (empty($banner)) {
                $banner = new Option;
                $banner->key = 'banner';
                $banner->lang = $request->lang;
            }
            $about_banner=  get_option('banner',true);

            $data['banner_header'] = $request->banner_header;
            $data['btnfirst'] = $request->btnfirst;
            $data['btnsecond'] = $request->btnsecond;
            $data['usedthis'] = $request->usedthis;
            $data['phone_image_1'] = $about_banner->phone_image_1 ?? null;
            $data['phone_image_2'] = $about_banner->phone_image_2 ?? null;
            $data['phone_image_3'] = $about_banner->phone_image_3 ?? null;

            if ($request->hasFile('phone_image_1')) {
                $phone_image_1 = $this->saveFile($request,'phone_image_1');
                $data['phone_image_1'] = $phone_image_1;

                $this->removeFile($about_banner->phone_image_1 ?? null);
            }

            if ($request->hasFile('phone_image_2')) {
                $phone_image_2 = $this->saveFile($request,'phone_image_2');
                $data['phone_image_2'] = $phone_image_2;

                $this->removeFile($about_banner->phone_image_2 ?? null);
            }

            if ($request->hasFile('phone_image_3')) {
                $phone_image_3 = $this->saveFile($request,'phone_image_3');
                $data['phone_image_3'] = $phone_image_3;

                $this->removeFile($about_banner->phone_image_3 ?? null);
            }

            $banner->value = json_encode($data);
            $banner->save();
           

            return response()->json(['message'=>__('Banner Section Updated...')]);
        }

        if ($request->type == 'features') {
            $data = $request->validate([
                'feature_image'  => ['image','max:2048'],
            ]);

            $features= Option::where('key', 'features')->where('lang',$request->lang)->first();

            if (empty($features)) {
                $features = new Option;
                $features->key = 'features';
                $features->lang = $request->lang;
            }
            $about_features=  get_option('features',true);

            $data['feature_header'] = $request->feature_header;
            $data['feature_subheader'] = $request->feature_subheader;
            $data['feature_image'] = $about_features->feature_image ?? null;

            $data['feature_1'] = $request->feature_1;
            $data['feature_1_details'] = $request->feature_1_details;
            $data['feature_2'] = $request->feature_2;
            $data['feature_2_details'] = $request->feature_2_details;
            $data['feature_3'] = $request->feature_3;
            $data['feature_3_details'] = $request->feature_3_details;
            $data['feature_4'] = $request->feature_4;
            $data['feature_4_details'] = $request->feature_4_details;

            if ($request->hasFile('feature_image')) {
                $feature_image = $this->saveFile($request,'feature_image');
                $data['feature_image'] = $feature_image;
                $this->removeFile($about_features->feature_image ?? null);
            }

            $features->value = json_encode($data);
            $features->save();
           
           //Cache::forget('features');

            return response()->json(['message'=>__('Features Section Updated...')]);

            
        }

        if ($request->type == 'about') {
            $data = $request->validate([
                'frame_image'  => ['image','max:2048'],
                'frame_image_2'  => ['image','max:2048'],
            ]);

            $about= Option::where('key', 'about_section')->where('lang',$request->lang)->first();

            if (empty($about)) {
                $about = new Option;
                $about->key = 'about_section';
                $about->lang = $request->lang;
            }
            $about_about=  get_option('about_section',true);

            $data['about_header'] = $request->about_header;
            $data['about_subheader'] = $request->about_subheader;
            $data['frame_image'] = $about_about->frame_image ?? null;
            $data['frame_image_2'] = $about_about->frame_image_2 ?? null;
            $data['about_api'] = $request->about_api;
            $data['satisfied_user'] = $request->satisfied_user;
            $data['customer_review'] = $request->customer_review;
            $data['about_countries'] = $request->about_countries;

            if ($request->hasFile('frame_image')) {
                $frame_image = $this->saveFile($request,'frame_image');
                $data['frame_image'] = $frame_image;
                $this->removeFile($about_about->frame_image ?? null);
            }
            if ($request->hasFile('frame_image_2')) {
                $frame_image_2 = $this->saveFile($request,'frame_image_2');
                $data['frame_image_2'] = $frame_image_2;
                $this->removeFile($about_about->frame_image_2 ?? null);
            }

            $about->value = json_encode($data);
            $about->save();

            return response()->json(['message'=>__('About Section Updated...')]);
        }

        if ($request->type == 'overview') {
            $data = $request->validate([
                'overview_image_1'  => ['image','max:2048'],
                'overview_image_2'  => ['image','max:2048'],
                'overview_image_3'  => ['image','max:2048'],
            ]);

            $overview= Option::where('key', 'overview')->where('lang',$request->lang)->first();

            if (empty($overview)) {
                $overview = new Option;
                $overview->key = 'overview';
                $overview->lang = $request->lang;
            }
            $about_overview=  get_option('overview',true);

            $data['overview_header'] = $request->overview_header;
            $data['overview_subheader'] = $request->overview_subheader;
            $data['overview_image_1'] = $about_overview->overview_image_1 ?? null;
            $data['overview_image_2'] = $about_overview->overview_image_2 ?? null;
            $data['overview_image_3'] = $about_overview->overview_image_3 ?? null;
            $data['overview_title_1'] = $request->overview_title_1;
            $data['overview_subtitle_1'] = $request->overview_subtitle_1;
            $data['overview_title_2'] = $request->overview_title_2;
            $data['overview_subtitle_2'] = $request->overview_subtitle_2;
            $data['overview_title_3'] = $request->overview_title_3;
            $data['overview_subtitle_3'] = $request->overview_subtitle_3;

            if ($request->hasFile('overview_image_1')) {
                $overview_image_1 = $this->saveFile($request,'overview_image_1');
                $data['overview_image_1'] = $overview_image_1;
                $this->removeFile($about_overview->overview_image_1 ?? null);
            }
            if ($request->hasFile('overview_image_2')) {
                $overview_image_2 = $this->saveFile($request,'overview_image_2');
                $data['overview_image_2'] = $overview_image_2;
                $this->removeFile($about_overview->overview_image_2 ?? null);
            }
            if ($request->hasFile('overview_image_3')) {
                $overview_image_3 = $this->saveFile($request,'overview_image_3');
                $data['overview_image_3'] = $overview_image_3;
                $this->removeFile($about_overview->overview_image_3 ?? null);
            }

            $overview->value = json_encode($data);
            $overview->save();
           
          // Cache::forget('overview');

            return response()->json(['message'=>__('Overview Section Updated...')]);

            
        }
        if ($request->type == 'how-it-works') {
            $data = $request->validate([
                'step_image_1'  => ['image','max:2048'],
                'step_image_2'  => ['image','max:2048'],
                'step_image_3'  => ['image','max:2048'],
                'video_image'  => ['image','max:2048'],
            ]);

            $work= Option::where('key', 'work')->where('lang',$request->lang)->first();

            if (empty($work)) {
                $work = new Option;
                $work->key = 'work';
                $work->lang = $request->lang;
            }
            $about_work=  get_option('work',true);

            $data['work_header'] = $request->work_header;
            $data['work_subheader'] = $request->work_subheader;
            $data['step_image_1'] = $about_work->step_image_1 ?? null;
            $data['step_title_1'] = $request->step_title_1;
            $data['step_subtitle_1'] = $request->step_subtitle_1;
            $data['step_description_1'] = $request->step_description_1;
            $data['step_image_2'] = $about_work->step_image_2 ?? null;
            $data['step_title_2'] = $request->step_title_2;
            $data['step_subtitle_2'] = $request->step_subtitle_2;
            $data['step_description_2'] = $request->step_description_2;
            $data['step_image_3'] = $about_work->step_image_3 ?? null;
            $data['step_title_3'] = $request->step_title_3;
            $data['step_subtitle_3'] = $request->step_subtitle_3;
            $data['step_description_3'] = $request->step_description_3;

            $data['video_image'] = $about_work->video_image ?? null;
            $data['video_header'] = $request->video_header;
            $data['video_url'] = $request->video_url;

            if ($request->hasFile('step_image_1')) {
                $step_image_1 = $this->saveFile($request,'step_image_1');
                $data['step_image_1'] = $step_image_1;
                $this->removeFile($about_work->step_image_1 ?? null);
            }
            if ($request->hasFile('step_image_2')) {
                $step_image_2 = $this->saveFile($request,'step_image_2');
                $data['step_image_2'] = $step_image_2;
                $this->removeFile($about_work->step_image_2 ?? null);
            }
            if ($request->hasFile('step_image_3')) {
                $step_image_3 = $this->saveFile($request,'step_image_3');
                $data['step_image_3'] = $step_image_3;
                $this->removeFile($about_work->step_image_3 ?? null);
            }

            if ($request->hasFile('video_image')) {
                $video_image = $this->saveFile($request,'video_image');
                $data['video_image'] = $video_image;
                $this->removeFile($about_work->video_image ?? null);
            }

            $work->value = json_encode($data);
            $work->save();
           
           //Cache::forget('work');

            return response()->json(['message'=>__('Work Section Updated...')]);

            
        }

        if ($request->type == 'download') {
            $data = $request->validate([
                'btn_image_1'  => ['image','max:2048'],
                'btn_image_2'  => ['image','max:2048'],
                'hero_image_1'  => ['image','max:2048'],
                'hero_image_2'  => ['image','max:2048'],
            ]);

            $download= Option::where('key', 'download')->where('lang',$request->lang)->first();

            if (empty($download)) {
                $download = new Option;
                $download->key = 'download';
                $download->lang = $request->lang;
            }
            $about_download=  get_option('download',true);

            $data['download_header'] = $request->download_header;
            $data['download_subheader'] = $request->download_subheader;
           // $data['btn_image_1'] = $about_download->btn_image_1 ?? null;
           // $data['btn_image_2'] = $about_download->btn_image_2 ?? null;
            $data['hero_image_1'] = $about_download->hero_image_1 ?? null;
            $data['hero_image_2'] = $about_download->hero_image_2 ?? null;

            //if ($request->hasFile('btn_image_1')) {
            //    $btn_image_1 = $this->saveFile($request,'btn_image_1');
            //    $data['btn_image_1'] = $btn_image_1;
            //    $this->removeFile($about_download->btn_image_1 ?? null);
           // }
           // if ($request->hasFile('btn_image_2')) {
            //   $btn_image_2 = $this->saveFile($request,'btn_image_2');
            //    $data['btn_image_2'] = $btn_image_2;
            //    $this->removeFile($about_download->btn_image_2 ?? null);
           // }
            if ($request->hasFile('hero_image_1')) {
                $hero_image_1 = $this->saveFile($request,'hero_image_1');
                $data['hero_image_1'] = $hero_image_1;
                $this->removeFile($about_download->hero_image_1 ?? null);
            }
            if ($request->hasFile('hero_image_2')) {
                $hero_image_2 = $this->saveFile($request,'hero_image_2');
                $data['hero_image_2'] = $hero_image_2;
                $this->removeFile($about_download->hero_image_2 ?? null);
            }

            $download->value = json_encode($data);
            $download->save();
           
           //Cache::forget('download');

            return response()->json(['message'=>__('Download Section Updated...')]);

            
        }
        
    }
}
