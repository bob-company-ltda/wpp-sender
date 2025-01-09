<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;
trait Uploader
{

    //upload file
    private function saveFile(Request $request, $input)
{
    $file = $request->file($input);

    // Generate a unique filename
    $filename = now()->timestamp . Str::random(20) . '.' . $file->getClientOriginalExtension();

    // Define the storage path within the public directory
    $path = 'uploads/' . date('Y') . '/' . date('m') . '/';

    // Move the file to the public/uploads directory
    $file->move(public_path($path), $filename);

    // Retrieve the asset link for the saved file
    $fileLink = asset($path . $filename);

    return $fileLink;
}

    

  private  function generateFileLink(Request $request,$file)
{
    // Assuming the file is saved in the public directory
    $publicPath = public_path();
    $filePath = $publicPath . 'uploads'.date('/y') . '/' . date('m') . '/' . $file;

    // Generate the file link
    $fileLink = asset($filePath);

    return $fileLink;
}


    //remove file
    public function removeFile($url=null)
    {
       if (empty($url)) {
           return true;
       }

       $fileName=explode('uploads', $url);
       if (isset($fileName[1])) {
         $exists_file='uploads'.$fileName[1];
         if (Storage::exists($exists_file)) {
            Storage::delete($exists_file);
         }
         return true;
       }

       return false;
    }


}