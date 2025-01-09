<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Traits\Dotenv;
use Http;
use File;
use ZipArchive;
use Session;
use Auth;
class AddonsController extends Controller
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
       $domain = url('/'); // Gets the current domain
    $external_user_id = Auth::id(); // Assuming the user is authenticated

    // Send a request to the main server with domain and external_user_id
    $response = Http::get('https://ionfirm.com/whatscloud/plugins', [
        'domain' => $domain,
        'external_user_id' => $external_user_id
    ]);

    if ($response->successful()) {
        $plugins = $response->json();
    } else {
        $plugins = []; // Fallback if the API call fails
    }
    
    
    return view('admin.addons.index', compact('plugins'));
    }

    

    /**
     * check new update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'plugin_zip' => 'required|mimes:zip|max:10240',  // Allow only ZIP files, max 10MB
    ]);

    // Get the uploaded file
    $file = $request->file('plugin_zip');
    
    // Define a storage path (optional: use `Storage` for cloud-based storage)
    $destinationPath = public_path('uploads/plugins/');
    $fileName = time() . '-' . $file->getClientOriginalName();
    
    // Move the uploaded file to the destination
    $file->move($destinationPath, $fileName);
    
    // Unzip the file
    $zip = new ZipArchive;
    $filePath = $destinationPath . $fileName;

    if ($zip->open($filePath) === true) {
        // Create the target directory (App\Includes)
        $extractToPath = app_path('Includes');
        
        // Extract the contents to the app directory
        $zip->extractTo($extractToPath);
        $zip->close();
        
        // Delete the ZIP file after extraction (optional)
        File::delete($filePath);
        return redirect()->back()->with('success', 'Plugin Installed and Activated Successfully!');
    } else {
        // If zip extraction fails
        return redirect()->back()->with('error', 'Failed to unzip the file. Please try again.');
    }
    }
    
}
