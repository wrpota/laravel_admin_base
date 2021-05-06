<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\SystemController;
use App\Services\PermissionService;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class PermissionCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a cache file for faster permission loading';

    /**
     * The PermissionService instance.
     *
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * Create a new command instance.
     *
     * @param PermissionService $permissionService
     */
    public function __construct(PermissionService $permissionService)
    {
        parent::__construct();

        $this->permissionService = $permissionService;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->call('permission:clear');

        //$this->permissionService->getCachedPermissionPath()

        //$adminControllerNameSpace = 'App\\Http\\Controllers\\Admin';
        //$adminControllerPath = app_path('Http/Controllers/Admin');
        //
        //$adminController = [];
        //foreach (Finder::create()->files()->name('*.php')->in($adminControllerPath) as $file) {
        //    $adminController[] =  $adminControllerNameSpace . '\\' . $file->getFilename();
        //}
        //ksort($adminController, SORT_NATURAL);
        //
        //file_put_contents($this->permissionService->getCachedPermissionPath(), '');
        $this->permissionService->handle();

        $this->info('Permission cache successfully!');
    }
}
