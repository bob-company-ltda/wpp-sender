<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Dotenv;
use Http;
use File;
use ZipArchive;
use Session;
class UpdateController extends Controller
{
    use Dotenv;

    public function __construct(){
      $this->middleware('permission:developer-settings'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.update.index');
    }

    

    /**
     * check new update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $site_key=env('SITE_KEY');
        $body['purchase_key'] = $site_key;
        $body['url'] = url('/');
        $body['current_version'] = env('APP_VERSION',1);
        

        $response =  \Http::get('https://ionfirm.com/verify/update.php',$body);
        $body = json_decode($response->body());
        if ($response->status() != 200) {
            \Session::flash('error',$body->message);

            return response()->json([
                'redirect'=>url('/admin/update'),
                'message'=>$body->message
            ],200);
        }

        \Session::put('update-data',[
                'message'=>$body->message,
                'version'=>$body->version
        ]);
        return response()->json([
                'redirect'=>url('/admin/update'),
            ],200);

    }

    public function update($version)
{
    $siteKey = env('SITE_KEY');
    $body['purchase_key'] = $siteKey;
    $body['url'] = url('/');
    $body['version'] = $version;
    $response = Http::get('https://ionfirm.com/verify/pull-update.php', $body);

    if ($response->status() == 200) {
        $updateData = json_decode($response->body(), true);

        if (isset($updateData['zip_url'])) {
            $zipUrl = $updateData['zip_url'];
            $tempZipFile = storage_path('app/update.zip');
            $tempExtractPath = storage_path('app/extracted');

            // Download the update.zip file
            file_put_contents($tempZipFile, fopen($zipUrl, 'r'));

            // Extract the contents of the zip file
            $zip = new ZipArchive;
            if ($zip->open($tempZipFile) === TRUE) {
                $zip->extractTo($tempExtractPath);
                $zip->close();

                // Update the files and folders in the Laravel project
                $this->updateFilesAndFolders($tempExtractPath);

                // Clean up the temporary files and folders
                File::delete($tempZipFile);
                File::deleteDirectory($tempExtractPath);

                $this->editEnv('APP_VERSION', $version);

                Session::forget('update-data');
                Session::flash('success', 'Successfully updated to ' . $version);

                return response()->json([
                    'redirect' => url('/admin/update'),
                ], 200);
            } else {
                // Error in extracting the zip file
                http_response_code(500);
                echo json_encode(['message' => 'Error extracting update.zip']);
                exit;
            }
        }
    }

    http_response_code(204); // No Content
    echo json_encode(['message' => 'No new update found.']);
    exit;
}

public function uploadUpdate(Request $request)
    {
        $request->validate([
            'update_file' => 'required|file|mimes:zip|max:102400',
        ]);

        $file = $request->file('update_file');
        $tempZipFile = storage_path('app/update.zip');
        $tempExtractPath = storage_path('app/extracted');

        // Move the uploaded file to the temporary storage path
        $file->move(storage_path('app'), 'update.zip');

        // Extract the zip file
        $zip = new ZipArchive;
        if ($zip->open($tempZipFile) === TRUE) {
            $zip->extractTo($tempExtractPath);
            $zip->close();

            // Update the files and folders in the Laravel project
            $this->updateFilesAndFolders($tempExtractPath);

            // Clean up the temporary files and folders
            File::delete($tempZipFile);
            File::deleteDirectory($tempExtractPath);

            return redirect()->back()->with('success', 'System successfully updated.');
        } else {
            return redirect()->back()->with('error', 'Error extracting the uploaded zip file.');
        }
    }

private function updateFilesAndFolders($sourcePath)
{
    $files = File::allFiles($sourcePath);
    $directories = File::directories($sourcePath);

    foreach ($files as $file) {
        $relativePath = str_replace($sourcePath, '', $file);
        $targetPath = base_path($relativePath);
        
        if (!is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }
        
        File::put($targetPath, File::get($file));
    }
    

    foreach ($directories as $directory) {
        $relativePath = str_replace($sourcePath, '', $directory);
        if (!File::exists(base_path($relativePath))) {
            File::makeDirectory(base_path($relativePath), 0755, true, true);
        }
    }
} 
}
