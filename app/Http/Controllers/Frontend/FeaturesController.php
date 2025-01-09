<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Plan;
use App\Traits\Seo;

class FeaturesController extends Controller
{
    use Seo;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $overview = get_option('overview',true,true);

        $this->metadata('seo_features');
        
        $theme_path = get_option('theme_path'); 
        $theme_path= empty($theme_path) ? 'frontend.index-1' : $theme_path;
        
        
        if($theme_path == 'frontend.index-1'){
             $features = Post::where('type','feature')->where('featured',1)->where('lang',app()->getLocale())->with('preview','excerpt')->latest()->take(6)->get();
        }else{
            $features = get_option('features', true, true);
        }
        
        if($theme_path == 'frontend.index-1'){
            return view('frontend.features',compact('features'));
        }else{
            return view('frontend.features.index',compact('features','overview'));   
        }
    }
}
