<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    public const SUPERADMIN_DASHBOARD = '/superadmin/dashboard';
    public const ADMIN_DASHBOARD = '/admin/dashboard';
    public const USER_DASHBOARD = '/user/dashboard';
    

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
     protected $namespace = 'App\\Http\\Controllers';


    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/user.php'));

            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/frontend.php'));    

        });
        
        
        
        $pluginBasePath = app_path('Includes');
        $plugins = File::directories($pluginBasePath);
        foreach ($plugins as $pluginPath) {
            $pluginName = basename($pluginPath); // Get the name of the plugin directory
            $this->runPluginSQLFile($pluginPath);
            // Define the routes directory for the plugin
            $routesPath = "$pluginPath/Routes";
            if (File::exists($routesPath) && File::isDirectory($routesPath)) {
                // Load the routes from each type of route file, checking for existence first
                $adminRouteFile = "$routesPath/Admin.php";
                $userRouteFile = "$routesPath/User.php";
                $webRouteFile = "$routesPath/Web.php";

                if (File::exists($adminRouteFile)) {
                    Route::middleware('web')->group($adminRouteFile);
                }

                if (File::exists($userRouteFile)) {
                    Route::middleware('web')->group($userRouteFile);
                }

                if (File::exists($webRouteFile)) {
                    Route::middleware('web')->group($webRouteFile);
                }
            } else {
                // Optionally log or handle plugins without a Routes directory
                // Log::info("Plugin '$pluginName' does not have a Routes directory.");
            }
        }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
    
    protected function runPluginSQLFile($pluginPath)
    {
        $sqlFilePath = "$pluginPath/db.sql";

        // Check if the SQL file exists
        if (File::exists($sqlFilePath)) {
            // Read the content of the SQL file
            $sqlContent = File::get($sqlFilePath);

            // Execute the SQL queries in the file
            try {
                DB::unprepared($sqlContent);
            } catch (\Exception $e) {
                \Log::error("Failed to execute SQL for plugin at $pluginPath: " . $e->getMessage());
            }
        }
    }
}