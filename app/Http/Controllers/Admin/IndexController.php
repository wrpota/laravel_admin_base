<?php

namespace App\Http\Controllers\Admin;

use App\Attributes\Annotation\Permission;
use App\Services\AdminService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @Permission(identification="index")
 * @package App\Http\Controllers\Admin
 */
class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     *
     * @Permission(identification="index")
     * @return View
     */
    public function index(): View
    {
        $user = Auth::guard('admin')->user();

        return view('admin.index.index', ['user' => $user]);
    }

    /**
     * 获取用户菜单
     *
     * @param AdminService $adminService
     *
     * @return JsonResponse
     */
    public function menu(AdminService $adminService): JsonResponse
    {
        return $adminService->menu();
    }

}
