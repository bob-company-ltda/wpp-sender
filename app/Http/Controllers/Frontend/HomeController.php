<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\Option;
use App\Models\Plan;
use App\Traits\Seo;
use Cache;
class HomeController extends Controller
{
    use Seo;

     /**
     * Display a home page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        $brands = Category::where('type','brand')->where('status',1)->latest()->get();

        

        $testimonials =  Post::where('type','testimonial')->with('preview','excerpt')->latest()->get();

        $faqs = Post::where('type','faq')->where('featured',1)->where('lang',app()->getLocale())->with('excerpt')->latest()->get();

        $plans = Plan::where('status',1)->where('is_featured',1)->latest()->get();

        $this->metadata('seo_home');

        $home = get_option('home-page',true,true);
        $brand_area = $home->brand->status ?? 'active';
        $account_area = $home->account_area->status ?? 'active';

        $banner = get_option('banner', true, true);
       

        $about = get_option('about_section',true,true);
        $overview = get_option('overview',true,true);
        $work = get_option('work',true,true);
        $download = get_option('download',true,true);

        $theme_path = get_option('theme_path'); 
        $theme_path= empty($theme_path) ? 'frontend.index' : $theme_path;
        if($theme_path == 'frontend.index-1'){
             $features = Post::where('type','feature')->where('featured',1)->where('lang',app()->getLocale())->with('preview','excerpt')->latest()->take(6)->get();
        }else{
            $features = get_option('features', true, true);
        }

        return view($theme_path,compact('brands','testimonials','faqs','plans','brand_area', 'banner','features','about','overview','work','download'));
    }
    


     /**
     * Display  about page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        $overview = get_option('overview',true,true);
        $about = get_option('about_section',true,true);
        $download = get_option('download',true,true);
        $banner = get_option('banner', true, true);

        $faqs = Post::where('type','faq')->where('featured',1)->where('lang',app()->getLocale())->with('excerpt')->latest()->get();

        $plans = Plan::where('status',1)->where('is_featured',1)->latest()->get();

         $this->metadata('seo_about');
         
         $theme_path = get_option('theme_path'); 
        $theme_path= empty($theme_path) ? 'frontend.index-1' : $theme_path;
        
        if($theme_path == 'frontend.index-1'){
            
        return view('frontend.about-2',compact('about','download','banner','faqs','overview'));
        
        }else{
            return view('frontend.about',compact('about','download','banner','faqs','plans'));
        }
    }


     /**
     * Display  faq page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {
        $faqs = Post::where('type','faq')->where('lang',app()->getLocale())->with('excerpt')->latest()->get();
        
        $this->metadata('seo_faq');

        return view('frontend.faq',compact('faqs'));
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function page($slug)
    {
        $page = Post::where('status',1)->where('type','page')->with('seo','description')->where('slug',$slug)->first();        
        abort_if(empty($page),404);
        
        $seo = json_decode($page->seo->value ?? '');

        $meta['title'] = $seo->title ?? '';
        $meta['description'] = $seo->description ?? '';
        $meta['tags'] = $seo->tags ?? '';

        $this->pageMetaData($meta);

        return view('frontend.page',compact('page'));
    }
}
