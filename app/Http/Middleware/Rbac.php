<?php

namespace App\Http\Middleware;

use App\Exceptions\RbacException;
use App\Services\AdminService;
use App\Services\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Rbac
{
    /**
     * @var AdminService
     */
    private $adminService;

    /**
     * @var PermissionService
     */
    private $permissionService;

    public function __construct(AdminService $adminService, PermissionService $permissionService)
    {
        $this->adminService = $adminService;
        $this->permissionService = $permissionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @throws RbacException
     */
    public function handle(Request $request, Closure $next)
    {
        $action = Route::current()->getAction();

        $permission = $this->permissionService->getPermissionByAction($action);
        // dd($permission);
        if ($permission['method'] == 'ALL' || $permission == $request->getMethod()) {
           $allPermission = array_column($this->adminService->getPermission(), null, 'identification');
           if (!isset($allPermission[$permission['identification']])) {
               //TODO 权限调整完毕后打开异常抛出
               throw new RbacException(RbacException::NOT_RULE);
           }
        }

        return $next($request);
    }
}
