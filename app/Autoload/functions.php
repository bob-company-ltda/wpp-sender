<?php 
use App\Models\Option;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CloudApi;
use App\Models\App;
use App\Models\Post;
use App\Models\Template;
use App\Models\Smstransaction;
use App\Models\Contact;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

if (!function_exists('transactionCharge')) {
    /**
     *  returnn transaction charge for sms
     * @param string $type
     * @return double
     */
    function transactionCharge($type)
    {
    	if ($type == 'custom_message') {
    		return 1;
    	}
    	elseif ($type == 'bulk_message') {
    		return 1;
    	}
    	elseif ($type == 'scheduled_message') {
    		return 1;
    	}

    }

}

if (!function_exists('badge')) {
    /**
     *  print badge
     * @param string $status
     * @return array
     */
    function badge($status)
    {
    	return $classes = [
    		0 => ['class' => 'badge-danger', 'text' => 'Rejected'],
    		1 => ['class' => 'badge-success', 'text' => 'Accepted'],
    		2 => ['class' => 'badge-danger', 'text' => 'Pending'],
    		'pending' => ['class' => 'badge-warning'],
    		'delivered' => ['class' => 'badge-success'],
    		'rejected' => ['class' => 'badge-danger'],
    		'Executed' => ['class' => 'badge-success'],
    	][$status];
    }

}

if (!function_exists('amount_format')) {
    /**
     *  format amount
     * @param string $amount
     * @param string $icon_type
     * @return string
     */
    function amount_format($amount=0, $icon_type = 'name')
    {
    	$currency = get_option('base_currency',true);
    	if ($icon_type == 'name') {
    		$currency = $currency->position == 'right' ? $currency->name.' '.number_format($amount,2)  :  number_format($amount,2).' '.$currency->name;
    	}
    	elseif ($icon_type == 'both') {
    		$currency = $currency->icon.number_format($amount,2).' '.$currency->name;
    	}
    	else{
    		$currency = $currency->position == 'right' ? number_format($amount,2).$currency->icon : $currency->icon.number_format($amount,2);
    	}

    	return $currency;
    }

}

if (!function_exists('planData')) {
    /**
     *  plan data
     * @param string $title
     * @param string or bool value
     * @return array
     */

    function planData($title, $value)
{
    // Check if the feature is a standard feature
    $standardFeatures = [
        'chatbot', 'bulk_message', 'run_campaign', 
        'template_message', 'access_chat_list',
    ];

    if (in_array($title, $standardFeatures)) {
        $data['is_bool'] = true;
        $data['title'] = $title;
        $data['value'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        return $data;
    }

    // Load plugins and check if the title matches a plugin feature
    $pluginBasePath = app_path('Includes');
    if (File::exists($pluginBasePath)) {
        foreach (File::directories($pluginBasePath) as $pluginPath) {
            $configPath = $pluginPath . '/config.php';
            if (File::exists($configPath)) {
                $pluginConfig = include $configPath;
                
                // If the plugin has the feature, set it up
                if (isset($pluginConfig['features']) && $pluginConfig['features'] === $title) {
                    $data['is_bool'] = true;
                    $data['title'] = $pluginConfig['name']; // Use plugin name for display
                    $data['value'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    return $data;
                }
            }
        }
    }

    // For other features, treat them as non-boolean and allow 'unlimited' if -1
    if ($value == -1) {
        $value = 'unlimited';
    }
    $data['value'] = null;
    $data['is_bool'] = false;
    $data['title'] = $title . ' (' . $value . ')';
    return $data;
}


}



if (!function_exists('getUserPlanData')) {
    /**
     * get user plan data
     * @param string $key
     * @param int $user_id nullable
     * @return boolean
     */

    function getUserPlanData($key, $user_id = null)
{
    $user = $user_id != null ? User::where('id', $user_id)->where('status', 1)->first() : Auth::user();
    
    if ($user->will_expire < now()) {
        return false;
    }

    $plan = json_decode($user->plan);
    $filterKey = $plan->$key ?? false;
    $filterKey = filterPlanData($key, $filterKey);

    // Initialize $rows with a default value
    
    if ($key === 'mechanism') {
        return isset($filterKey['value']) ? $filterKey['value'] : false;
    }
    
    $rows = 0;

    if ($filterKey['is_bool'] == false) {
        if ($filterKey['value'] == 'unlimited') {
            return true;
        } else {
            // Check for each specific key and set $rows
            if ($key == 'cloudapi_limit') {
                $rows = CloudApi::where('user_id', $user->id)->count();
            } elseif ($key == 'apps_limit') {
                $rows = App::where('user_id', $user->id)->count();
            } elseif ($key == 'template_limit') {
                $rows = Template::where('user_id', $user->id)->count();
            } elseif ($key == 'messages_limit') {
                $rows = Smstransaction::where('user_id', $user->id)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->count();
            } elseif ($key == 'contact_limit') {
                $rows = Contact::where('user_id', $user->id)->count();
            }

            // Compare $rows with $filterKey['value'] only if it is set
            if ($rows >= (int) $filterKey['value']) {
                return false;
            } else {
                return true;
            }
        }
    }

    return $filterKey['value'];
}



}

if (!function_exists('filterPlanData')) {
    /**
     * get filtered plan data
     * @param string $title
     * @param string $value
     * @return array
     */
    function filterPlanData($title, $value)
{
    // Define a list of standard features that are boolean-based
    $standardFeatures = [
        'chatbot', 'bulk_message', 'run_campaign', 
        'template_message', 'access_chat_list',
    ];

    // Check if the feature is in standard features list
    if (in_array($title, $standardFeatures)) {
        $data['is_bool'] = true;
        $data['value'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        return $data;
    }

    // Load plugin features dynamically
    $pluginBasePath = app_path('Includes');
    if (File::exists($pluginBasePath)) {
        foreach (File::directories($pluginBasePath) as $pluginPath) {
            $configPath = $pluginPath . '/config.php';
            if (File::exists($configPath)) {
                $pluginConfig = include $configPath;

                // Check if this plugin's feature matches the title
                if (isset($pluginConfig['id']) && $pluginConfig['id'] === $title) {
                    $data['is_bool'] = true;
                    $data['value'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    return $data;
                }
            }
        }
    }

    // Handle non-boolean values or features not found in standard features or plugins
    if ($value == -1) {
        $data['value'] = 'unlimited';
    } else {
        $data['value'] = (int)$value;
    }
    $data['is_bool'] = false;

    return $data;
}


}



if (!function_exists('get_option')) {
    /**
     * Get Settings From Database
     * @param $key
     * @param bool $decode
     * @param $locale
     * @return mixed
     */
    function get_option($key, bool $decode = false, $locale = false, $associative = false): mixed
{
    $query = Option::query();

    if ($locale !== false) {
        $query->where('lang', current_locale());
    }

    $option = $query->where('key', $key)->first();

    if (!$option) {
        return null;
    }

    return $decode ? json_decode($option->value, $associative) : $option->value;
}

}

if (!function_exists('customFunction')) {
    function customFunction($folder, $file, $variable = null, Request $request = null, $template= false) {
        
        if (!class_exists('DefaultResponse')) {
            class DefaultResponse {
                public function status() {
                    return 500;
                }
            }
        }
        
        $path = app_path("Includes/{$folder}/Controllers/{$file}.php");
        if (file_exists($path)) {
            return include $path;
        }
       return new DefaultResponse();
    }
}


if (!function_exists('views')) {
    /**
     * Load a view from a custom folder in App/Includes dynamically.
     *
     * @param string $folder The folder name in App/Includes
     * @param string $view The view file name without .blade.php
     * @param array $data Data to pass to the view
     * @return \Illuminate\View\View
     */
    function views($folder, $view, $data = [])
    {
        $viewPath = app_path("Includes/{$folder}/Views/{$view}.blade.php");

        if (!file_exists($viewPath)) {
            abort(404, "View [{$viewPath}] not found.");
        }

        return view()->file($viewPath, $data);
    }
}


if (!function_exists('cache_remember')) {
    /**
     * This function will remember the cache
     * @param string $key
     * @param callable $callback
     * @param integer $ttl
     * @return mixed
     */
    function cache_remember(string $key, callable $callback, int $ttl = 1800): mixed
    {
    	return cache()->remember($key, env('CACHE_LIFETIME', $ttl), $callback);
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get Current Locale
     * Return current locale|lang
     * @return string|null
     */
    function current_locale()
    {
    	return app()->getLocale();
    }
}

if (!function_exists('PrintMenu')) {
    /**
     * Get Dynamic Menu From Database
     * @param $position
     * @param string $path
     * @return Factory|\Illuminate\Contracts\View\View|Application
     */
    function PrintMenu($position, string $path = 'frontend.menu')
{
    $locale = current_locale();

    $menus = Menu::where('position', $position)->where('lang', $locale)->first();
    $data['data'] = json_decode($menus->data ?? '');
    $data['name'] = $menus->name ?? '';

    return view($path . '.main-menu', compact('data'));
}

}

if (!function_exists('PrintExtraMenu')) {
    
    function PrintExtraMenu($position)
    {
        $locale = current_locale();

        $menus = Menu::where('position', $position)->where('lang', $locale)->first();
        $data = json_decode($menus->data ?? '');

        $html = '';
        if (!empty($data)) {
            foreach ($data as $row) {
                $html .= '<li>';
                if (isset($row->href)) {
                    $html .= '<a href="' . url($row->href) . '">' . __($row->text ?? '') . '</a>';
                } else {
                    $html .= __($row->text ?? '');
                }
                $html .= '</li>';
            }
        }

        return $html;
    }
}


if (!function_exists('PrintPages')) {
    
    function PrintPages()
    {
        $locale = current_locale();

        $pages = Post::where('type', 'page')->where('status', 1)->paginate(5);

        $html = '';
        if (!empty($pages)) {
            foreach ($pages as $row) {
                $html .= '<li>';
                if (isset($row->slug)) {
                    $html .= '<a href="' . url('page/' . $row->slug) . '">' . __(Str::limit($row->title, 50) ?? '') . '</a>';
                } else {
                    $html .= __(Str::limit($row->title, 50) ?? '');
                }
                $html .= '</li>';
            }
        }

        return $html;
    }
}




if (!function_exists('filterXss')) {
    /**
     * filter script code
     * @param $string
     */
function filterXss($string=''){

    $string = str_replace('</script>', "", $string);
    $string = str_replace('<script>', "", $string);

    return $string;
}

}

?>