<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckController extends Command
{
    protected $signature = 'check:install-controller';
    protected $description = 'Check if InstallController exists';

    public function handle()
    {
        $installControllerPath = app_path('Http/Controllers/Installer/InstallController.php');

        if (file_exists($installControllerPath)) {
            $this->info('InstallController found. Your application is secure.');
        } else {
            $this->error('InstallController not found. Your application may be at risk.');
        }
    }
}
