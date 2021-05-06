<?php

namespace App\Console\Commands;

use App\Services\PermissionService;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PermissionClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the permission cache file';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The PermissionService instance.
     *
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * Create a new config clear command instance.
     *
     * @param Filesystem $files
     * @param PermissionService $permissionService
     */
    public function __construct(Filesystem $files, PermissionService $permissionService)
    {
        parent::__construct();

        $this->files = $files;

        $this->permissionService = $permissionService;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->files->delete($this->permissionService->getCachedPermissionPath());

        $this->info('Permission cache cleared!');
    }
}
