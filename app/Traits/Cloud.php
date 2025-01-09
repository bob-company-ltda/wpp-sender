<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\CloudApi;
use App\Models\Template;
use App\Models\Smstransaction;
use Http;
trait Cloud
{
    private function formatCloudTemplateArray($data, $type){
    $content = [];

    if ($type === 'text-with-template') {
        $templateName = $data['template_name'];
        $category = $data['category'];

        // Save the template name and category to the database

        $headerType = $data['header_type'];
        $headerText = $data['text_header'];
        $headerParameters = [];

        for ($i = 1; isset($data['text_parameter_'.$i]); $i++) {
            $headerParameters[] = $data['text_parameter_'.$i];
        }

        // Save the header type, header text, and header parameters to the database

        $mediaType = $data['media_type'];

        $messageText = $data['message'];
        $messageParameters = [];

        for ($i = 1; isset($data['message_parameter_'.$i]); $i++) {
            $messageParameters[] = $data['message_parameter_'.$i];
        }

        // Save the media type, message text, and message parameters to the database

        $footerText = $data['footer_text'];

        // Save the footer text to the database

        $buttons = $data['buttons'];
        $templateButtons = [];

        foreach ($buttons as $key => $button) {
            $buttonType = '';
            $buttonActionContent = '';

            if ($button['type'] === 'urlButton') {
                $buttonType = 'url';
                $buttonActionContent = $button['action'];
            } elseif ($button['type'] === 'callButton') {
                $buttonType = 'phoneNumber';
                $buttonActionContent = $button['action'];
            } else {
                $buttonType = 'id';
                $buttonActionContent = 'action-id-'.$key;
            }

            $buttonActions = [
                'displayText' => $button['displaytext'],
                $buttonType => $buttonActionContent,
            ];

            $buttonContext = [
                'index' => $key,
                $button['type'] => $buttonActions,
            ];

            $templateButtons[] = $buttonContext;
        }

        // Save the template buttons to the database

        $content = [
            'templateName' => $templateName,
            'category' => $category,
            'headerType' => $headerType,
            'headerText' => $headerText,
            'headerParameters' => $headerParameters,
            'mediaType' => $mediaType,
            'messageText' => $messageText,
            'messageParameters' => $messageParameters,
            'footerText' => $footerText,
            'templateButtons' => $templateButtons,
        ];

        // Save the content to the database
    }
    
    if ($type === 'meta-template'){
        
        $content = $data;
    }

    return $content;
}




    private function formatArray($data,$message,$type)
    {

        if ($type == 'plain-text') {
            $content['text']=$message;
        }
        elseif ($type == 'text-with-media') {
            $content['caption']=$message;
            $explode=explode('.', $data['attachment']);
            $file_type=strtolower(end($explode));
            $extentions=[
                'jpg'=>'image',
                'jpeg'=>'image',
                'png'=>'image',
                'webp'=>'image',
                'pdf'=>'document',
                'docx'=>'document',
                'xlsx'=>'document',
                'csv'=>'document',
                'txt'=>'document'
            ];
            
            $content[$extentions[$file_type]]=['url' => asset($data['attachment'])];
           
        }
        elseif ($type == 'text-with-image') {
            $content['caption']=$message;
            $explode=explode('.', $data['attachment']);
            $file_type=strtolower(end($explode));
            $extentions=[
                'jpg'=>'image',
                'jpeg'=>'image',
                'png'=>'image',
            ];
            
            $content[$extentions[$file_type]]=['url' => asset($data['attachment'])];
        }
        elseif ($type == 'text-with-button') {
            $buttons=[];
            foreach ($data['buttons'] as $key => $button) {
                $button_content['buttonId']='id'.$key;
                $button_content['buttonText']= array('displayText' => $button);
                $button_content['type']=1;

                array_push($buttons, $button_content);
            }


           $content['text']=$message;
           $content['footer']=$data['footer_text'];
           $content['buttons']=$buttons;
           $content['headerType']=1;
        }elseif ($type == 'text-with-location') {
            $content['location']=array(
                'degreesLatitude'=>$data['degreesLatitude'],
                'degreesLongitude'=>$data['degreesLongitude']
            );
        }
        elseif ($type == 'text-with-vcard') {
            $vcard='BEGIN:VCARD\n' // metadata of the contact card
            . 'VERSION:3.0\n' 
            . 'FN:'.$data['full_name'].'\n' // full name
            . 'ORG:'.$data['org_name'].';\n' // the organization of the contact
            . 'TEL;type=CELL;type=VOICE;waid='.$data['contact_number'].':'.$data['wa_number'].'\n'
            . 'END:VCARD';

           
            $content = [
             "contacts" => [
               "displayName" => "maruf", 
               "contacts" => [[$vcard]] 
             ] 
            ]; 
        }
        elseif ($type == 'text-with-list') {
            
            $templateButtons=[];

            foreach ($data['section'] as $section_key => $sections) {

               $rows=[];

               foreach ($sections['value'] as $value_key => $value) {
                
                   $rowArr['title']=$value['title'];
                   $rowArr['rowId']='option-'.$section_key.'-'.$value_key;

                   if ($value['description'] != null) {
                       $rowArr['description']=$value['description'];
                   }
                   array_push($rows, $rowArr);
                   $rowArr=[];
               }

               $row['title']=$sections['title'];
               $row['rows']=$rows;


              array_push($templateButtons, $row);
              $row=[];
            }
          
             $content = [
               "text" => $message, 
               "footer" =>  $data['footer_text'], 
               "title" => $data['header_title'], 
               "buttonText" =>$data['button_text'], 
               "sections" => $templateButtons
            ]; 
           
           
        }


        return $content;
    }

    private function saveTemplate($data,$message,$type,$user_id,$template_id=null)
    {
       if ($template_id == null) {
          $template= new Template;
       }
       else{
          $template=  Template::findorFail($template_id);
          $template->status=isset($data['status']) ? 1 : 0;
       }
       
       $template->title=$data['template_name'];
       $template->user_id=$user_id;
       $template->body=$this->formatArray($data,$message,$type);
       $template->type=$type;
       $template->save();

       return true;
    }

    private function saveCloudTemplate($data,$type,$user_id,$templateStatus, $cloud_id, $template_id=null)
    {
        //dd($data);
        if ($template_id == null) {
            $template= new Template;
            $template->uuid = $data['id'] ?? $templateStatus['id'];
         }
         else{
            $template=  Template::findorFail($template_id);
         }
       $template->user_id=$user_id;
       $template->title=$data['name'] ?? $data['template_name'];
       $template->body=$this->formatCloudTemplateArray($data,$type);
       $template->type=$type;
       $template->cloudapi_id = $cloud_id;
       $template->status= $templateStatus ?? 0;
       $template->save();
       return true;
    }

    private function saveFile(Request $request,$input)
    {
        $file = $request->file($input);
        $ext = $file->extension();
        $filename = now()->timestamp.'.'.$ext;

        $path = 'uploads/message/' . \Auth::id() . date('/y') . '/' . date('m') . '/';
        $filePath = $path.$filename;
        

       
        Storage::put($filePath, file_get_contents($file));

        return Storage::url($filePath);
    }

    private function saveFileExt(Request $request,$input)
    {
        
        $file = $request->file($input);
        $ext = $file->extension();
        $filename = now()->timestamp.'.'.$ext;
        return $filename;
    }



    private function formatBody($context='', $user_id)
    {
        if ($context == '') {
            return $context;
        }

        $user=User::where('id',$user_id)->first();

        if (empty($user)) {
           return $context;
        }
        else{
           return $context; 
        }
    }

    private function formatText($context='', $contact_data = null,$senderdata = null)
    {
       if ($context == '') {
            return $context;
       }
       if ($contact_data != null) {
           $name=$contact_data['name'] ?? '';
           $phone=$contact_data['phone'] ?? '';
           $param1 = $contact_data['param1'] ?? '';
           $param2 = $contact_data['param2'] ?? '';
           $param3 = $contact_data['param3'] ?? '';
           $param4 = $contact_data['param4'] ?? '';
           $param5 = $contact_data['param5'] ?? '';
           $param6 = $contact_data['param6'] ?? '';
           $param7 = $contact_data['param7'] ?? '';

           $context=str_replace('{name}',$name,$context);
           $context=str_replace('{phone_number}',$phone,$context);
           $context=str_replace('{param1}',$param1,$context);
           $context=str_replace('{param2}',$param2,$context);
           $context=str_replace('{param3}',$param3,$context);
           $context=str_replace('{param4}',$param4,$context);
           $context=str_replace('{param5}',$param5,$context);
           $context=str_replace('{param6}',$param6,$context);
           $context=str_replace('{param7}',$param7,$context);

       }

       if ($senderdata != null) {
           $sender_name=$senderdata['name'] ?? '';
           $sender_phone=$senderdata['phone'] ?? '';
           $sender_email=$senderdata['email'] ?? '';

           $context=str_replace('{my_name}',$sender_name,$context);
           $context=str_replace('{my_contact_number}',$sender_phone,$context);
           $context=str_replace('{my_email}',$sender_email,$context);
       }
      
       return $context;


    }

    private function formatCustomText($context='', $replaceableData = [])
    {
        $filteredContent = $context;
        
        foreach ($replaceableData ?? [] as $key => $value) {
           $filteredContent = str_replace($key, $value, $filteredContent);
        }

        return $filteredContent;

    }

    private function saveLog($data)
    {
        $log= new Smstransaction;
        $log->user_id = $data['user_id'] ?? null;
        $log->cloudapi_id = $data['cloudapi_id'] ?? null;
        $log->app_id = $data['app_id'] ?? null;
        $log->from = $data['from'] ?? null;
        $log->to = $data['to'] ?? null;
        $log->template_id = $data['template_id'] ?? null;
        $log->type = $data['type'] ?? null;
        $log->wamid = $data['wamid'] ?? null;
        $log->status = $data['status'] ?? null;
        $log->campaign_id = $data['campaign_id'] ?? null;
        $log->save();
    }

}