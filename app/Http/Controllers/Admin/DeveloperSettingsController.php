<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Dotenv;
use DateTimeZone;
use Str;
use File;
class DeveloperSettingsController extends Controller
{
    use Dotenv;

    public function __construct(){
      $this->middleware('permission:developer-settings'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id == 'app-settings') {
           $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
           $languages = get_option('languages',true);

           return view('admin.developer.app',compact('id','tzlist','languages'));
        }

        elseif ($id == 'mail-settings') {
           $mailDriver = env('MAIL_DRIVER_TYPE') == 'MAIL_DRIVER' ? env('MAIL_DRIVER') : env('MAIL_MAILER');
           return view('admin.developer.smtp',compact('id','mailDriver'));
        }

        elseif ($id == 'storage-settings') {
           return view('admin.developer.storage',compact('id'));
        }
        elseif($id == 'embedded-signup'){
           return views('EmbeddedSignup', 'config', compact('id'));
        }
        elseif($id == 'whatsapp-web'){
            return views('WhatsAppWeb', 'server', compact('id'));
        }

        abort(404);
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
        if ($id == 'app-settings') {
            
            $this->editEnv('APP_NAME', Str::slug($request->name));
            $this->editEnv('APP_DEBUG', filter_var($request->app_debug,FILTER_VALIDATE_BOOLEAN),true);
            $this->editEnv('MAIL_VERIFICATION', filter_var($request->mail_verification,FILTER_VALIDATE_BOOLEAN),true);
            $this->editEnv('TIME_ZONE', $request->timezone);
            $this->editEnv('DEFAULT_LANG', $request->default_lang ?? 'en');

            
           return response()->json(['message'=>__('Global Settings Updated...')]);
        }

        elseif ($id == 'storage-settings') {
            $this->editEnv('FILESYSTEM_DISK', $request->FILESYSTEM_DISK);
            $this->editEnv('WAS_ACCESS_KEY_ID', $request->WAS_ACCESS_KEY_ID);
            $this->editEnv('SECRET_ACCESS_KEY', $request->SECRET_ACCESS_KEY);
            $this->editEnv('WAS_DEFAULT_REGION', $request->WAS_DEFAULT_REGION);
            $this->editEnv('WAS_BUCKET', $request->WAS_BUCKET);
            $this->editEnv('WAS_ENDPOINT', $request->WAS_ENDPOINT);

            return response()->json(['message'=>__('Storage Settings Updated...')]);
        }

        elseif ($id == 'mailchimp') {
            $this->editEnv('NEWSLETTER_API_KEY', $request->NEWSLETTER_API_KEY);
            $this->editEnv('NEWSLETTER_LIST_ID', $request->NEWSLETTER_LIST_ID);
            $this->editEnv('NEWSLETTER_ENDPOINT', $request->NEWSLETTER_ENDPOINT);
            

            return response()->json(['message'=>__('Mailchimp Settings Updated...')]);
        }

        elseif ($id == 'mail-settings') {


            $this->editEnv('QUEUE_MAIL', filter_var($request->QUEUE_MAIL,FILTER_VALIDATE_BOOLEAN),true);
            $this->editEnv('MAIL_DRIVER_TYPE', $request->MAIL_DRIVER_TYPE);
            $this->editEnv($request->MAIL_DRIVER_TYPE, $request->MAIL_DRIVER);
            $this->editEnv('MAIL_HOST', $request->MAIL_HOST);
            $this->editEnv('MAIL_PORT', $request->MAIL_PORT);
            $this->editEnv('MAIL_USERNAME', $request->MAIL_USERNAME);
            $this->editEnv('MAIL_PASSWORD', $request->MAIL_PASSWORD);
            $this->editEnv('MAIL_ENCRYPTION', $request->MAIL_ENCRYPTION);
            $this->editEnv('MAIL_FROM_ADDRESS', $request->MAIL_FROM_ADDRESS);
            $this->editEnv('MAIL_FROM_NAME', $request->MAIL_FROM_NAME);            
            $this->editEnv('MAIL_TO', $request->MAIL_TO);

            return response()->json(['message'=>__('Mail Settings Updated...')]);
        }
        
        elseif ( $id == 'embedded-signup'){
            $response=customFunction('EmbeddedSignup', 'config', null, $request);
            if ($response->status() === 200) {
            return response()->json(['message'=>__('Embedded Signup Settings Updated...')]);
            }else{
                return response()->json(['error'=>__('something wrong')]);
            }
        }
        elseif ( $id == 'whatsapp-web'){
            $response=customFunction('WhatsAppWeb', 'config', null, $request);
            if ($response->status() === 200) {
            return response()->json(['message'=>__('WhatsApp Web Settings Updated...')]);
            }else{
                return response()->json(['error'=>__('something wrong')]);
            }
        }
    }

}
