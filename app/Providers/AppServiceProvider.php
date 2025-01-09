<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
{
    Schema::defaultStringLength(191);
    
    $pluginConfigs = [];
    $pluginBasePath = app_path('Includes');
    
    foreach (File::directories($pluginBasePath) as $pluginPath) {
        $configPath = $pluginPath . '/config.php';
        
        if (File::exists($configPath)) {
            // Merge each plugin's configuration into an array
            $pluginConfig = include $configPath;
            $pluginConfigs[$pluginConfig['id']] = $pluginConfig;
        }

        // Register each plugin as a custom namespace for views
        $pluginName = basename($pluginPath);
        view()->addNamespace($pluginName, $pluginPath . '/Views');
    }

    // Share the plugin configurations with all views
    View::share('pluginConfigs', $pluginConfigs);

    // Define the dynamicInclude Blade directive
    Blade::directive('dynamicInclude', function ($expression) {
        // Split the expression into plugin name and view name
        list($plugin, $view) = explode(',', str_replace(['(', ')', ' '], '', $expression));

        // Return the code to render the view with the custom namespace
        return "<?php echo view(trim($plugin).'::'.trim($view))->render(); ?>";
    });
}

}
