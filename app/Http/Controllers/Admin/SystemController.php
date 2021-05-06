<?php

namespace App\Http\Controllers\Admin;

use App\Attributes\Annotation\Permission;
use App\Services\AdminService;
use App\Services\MenuService;
use App\Services\RoleService;
use App\Services\PermissionService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SystemController extends BaseController
{
    /**
     * 管理员
     *
     * @param Request $request
     * @param AdminService $adminService
     * @Permission(identification="admin")
     * @return View|JsonResponse
     */
    public function admin(Request $request, AdminService $adminService)
    {
        if ($request->get('list')) {
            $res = $adminService->getList($request);
            foreach ($res as $admin) {
                $admin->role;
            }

            return $this->ajaxSuccess($res->items(), $res->total());
        }

        return $this->view('admin.system.admin');
    }

    /**
     * 修改管理员状态
     *
     * @param int $id
     * @param Request $request
     * @param AdminService $adminService
     * @Permission(identification="changeAdmin")
     * @return JsonResponse
     * @throws ValidationException
     */
    public function changeAdmin(int $id, Request $request, AdminService $adminService)
    {
        $data = $this->validate($request, [
            'status' => 'nullable|in:1,2',
        ],[
            'status.in' => '参数非法',
        ]);

        $result = $adminService->changeStatus($id, $data['status']);

        return $this->ajax($result, $adminService);
    }

    /**
     * 编辑管理员
     *
     * @param Request $request
     * @param AdminService $adminService
     * @param RoleService $roleService
     * @Permission(identification="editAdmin")
     * @return Application|Factory|View|JsonResponse
     * @throws ValidationException
     */
    public function editAdmin($id, Request $request, AdminService $adminService, RoleService $roleService)
    {
        if ($request->isMethod('POST')) {
            $validate = [
                'username' => 'required|max:44',
                'roleIds' => 'array',
            ];
            if ($id) {
                $validate['password'] = 'confirmed';
            }
            $data = $this->validate($request, $validate,[
                'username.max' => '用户名输入过长',
            ]);

            $result = $adminService->edit($id, $data);

            return $this->ajax($result, $adminService);
        }

        $admin = $adminService->model->find($id);

        $role = $roleService->getEnableList();
        $hasRoles = !empty($admin) ? array_column($admin->role->toArray(), null, 'id') : [];

        return $this->view('admin.system.operate.admin', compact('admin', 'role', 'hasRoles'));
    }

    /**
     * 删除用户
     *
     * @param int $id
     * @param AdminService $adminService
     * @Permission(identification="delAdmin")
     * @return JsonResponse
     */
    public function delAdmin(int $id, AdminService $adminService)
    {
        $result = $adminService->changeStatus($id, 0);

        return $this->ajax($result, $adminService);
    }

    /**
     * 菜单
     *
     * @param Request $request
     * @Permission(identification="menu")
     * @return View|JsonResponse
     */
    public function menu(Request $request)
    {
        if ($request->get('list')) {

            $res = app(MenuService::class)->getList();

            return $this->ajaxSuccess($res);
        }

        return $this->view('admin.system.menu');
    }

    /**
     * 编辑菜单
     *
     * @param $id
     * @param Request $request
     * @param MenuService $menuService
     * @Permission(identification="editMenu")
     * @return Application|Factory|View|JsonResponse
     * @throws ValidationException
     */
    public function editMenu($id, Request $request, MenuService $menuService)
    {
        if ($request->isMethod('POST')) {
            $menu = $this->validate($request, [
                'pid' => [
                    'required',
                    'int',
                    function($attribute, $value, $fail) {
                        if ($value) {
                            Rule::exists('menu')->where(function ($query) use ($value) {
                                $query->where('id', $value);
                            });
                        }
                    },
                ],
                'title' => 'required|max:44',
                'href' => 'required|max:255',
                'icon' => 'required|max:255',
                'type' => 'required',
                'open_type' => 'required|in:_iframe,_blank',
                'sort' => 'required|int',
            ],[
                'open_type.in' => '请选择正确的打开类型',
                'sort.int' => '排序只能输入整数',
            ]);
            $result = $menuService->edit($id, $menu);

            return $this->ajax($result, $menuService);
        }

        $menu = $menuService->model->find($id);
        $menuTree = $menuService->getList([]);

        return $this->view('admin.system.operate.menu', compact('menu', 'menuTree'));
    }

    /**
     * 删除菜单
     *
     * @param int $id
     * @param MenuService $menuService
     * @Permission(identification="delMenu")
     * @return JsonResponse
     */
    public function delMenu($id, MenuService $menuService)
    {
        $result = $menuService->delete($id);

        return $this->ajax($result, $menuService);
    }

    /**
     * 修改菜单内容
     *
     * @param int $id
     * @param Request $request
     * @param MenuService $menuService
     * @Permission(identification="changeMenu")
     * @return JsonResponse
     * @throws ValidationException
     */
    public function changeMenu(int $id, Request $request, MenuService $menuService)
    {
        $menu = $this->validate($request, [
            'status' => 'nullable|in:1,2',
            'sort' => 'nullable|int',
        ],[
            'status.in' => '参数非法',
            'sort.int' => '排序只能输入整数',
        ]);

        $result = $menuService->edit($id, $menu);

        return $this->ajax($result, $menuService);
    }

    /**
     * 权限列表
     * @param Request $request
     * @Permission(identification="permission")
     * @return View|JsonResponse
     */
    public function permission(Request $request)
    {
        if ($request->get('list')) {

            $res = app(PermissionService::class)->getList();

            return $this->ajaxSuccess($res);
        }

        return $this->view('admin.system.permission');
    }

    /**
     * 修改权限信息
     *
     * @param Request $request
     * @param PermissionService $permissionService
     * @Permission(identification="editPermission")
     * @return Application|Factory|View|JsonResponse
     * @throws ValidationException
     */
    public function editPermission($id, Request $request, PermissionService $permissionService)
    {
        if ($request->isMethod('POST')) {
            $menu = $this->validate($request, [
                'pid' => [
                    'required',
                    'int',
                    function($attribute, $value, $fail) {
                        if ($value) {
                            Rule::exists('menu')->where(function ($query) use ($value) {
                                $query->where('id', $value);
                            });
                        }
                    },
                ],
                'name' => 'required|max:44',
                'identification' => 'required|max:44',
                'remark' => 'nullable|max:255',
                'sort' => 'required|int',
            ],[
                'identification.max' => '权限标识输入过长',
                'remark.max' => '备注输入过长',
                'sort.int' => '排序只能输入整数',
            ]);
            $result = $permissionService->edit($id, $menu);

            return $this->ajax($result, $permissionService);
        }

        $permission = $permissionService->model->find($id);
        $permissionTree = $permissionService->getList([]);

        return $this->view('admin.system.operate.permission', compact('permission', 'permissionTree'));
    }

    /**
     * 编辑权限菜单
     *
     * @param $id
     * @param Request $request
     * @param PermissionService $permissionService
     * @Permission(identification="permissionMenu")
     * @return View|JsonResponse
     */
    public function permissionMenu($id, Request $request, PermissionService $permissionService, MenuService $menuService)
    {
        $permission = $permissionService->model->find($id);

        if (empty($permission)) {
            abort(404);
        }
        if ($request->isMethod('PUT')) {
            $menuIds = $request->get('menuIds', []);
            $result = $permissionService->editMenu($permission, $menuIds);

            return $this->ajax($result, $permissionService);
        }
        $checkMenus = $permission->menu->pluck('id')->toArray();
        $menuTree = $menuService->getList([], $checkMenus, true);

        return $this->view('admin.system.operate.permissionMenu', compact('permission', 'menuTree', 'id'));
    }

    /**
     * 删除权限
     *
     * @param int $id
     * @param PermissionService $permissionService
     * @Permission(identification="delPermission")
     * @return JsonResponse
     */
    public function delPermission($id, PermissionService $permissionService)
    {
        $result = $permissionService->delete($id);

        return $this->ajax($result, $permissionService);
    }

    /**
     * 修改权限内容
     *
     * @param int $id
     * @param Request $request
     * @param PermissionService $permissionService
     * @Permission(identification="changePermission")
     * @return JsonResponse
     * @throws ValidationException
     */
    public function changePermission(int $id, Request $request, PermissionService $permissionService)
    {
        $menu = $this->validate($request, [
            'status' => 'nullable|in:1,2',
            'sort' => 'nullable|int',
        ],[
            'status.in' => '参数非法',
            'sort.int' => '排序只能输入整数',
        ]);

        $result = $permissionService->edit($id, $menu);

        return $this->ajax($result, $permissionService);
    }

    /**
     * 角色列表
     * @param Request $request
     * @Permission(identification="role")
     * @return View|JsonResponse
     */
    public function role(Request $request)
    {
        if ($request->get('list')) {

            $res = app(RoleService::class)->getList($request);

            return $this->ajaxSuccess($res->items(), $res->total());
        }

        return $this->view('admin.system.role');
    }

    /**
     * 修改角色信息
     *
     * @param Request $request
     * @param RoleService $roleService
     * @return View|JsonResponse
     * @Permission(identification="editRole")
     * @throws ValidationException
     */
    public function editRole($id, Request $request, RoleService $roleService)
    {
        if ($request->isMethod('POST')) {
            $menu = $this->validate($request, [
                'name' => 'required|max:44',
                'remark' => 'nullable|max:255',
            ]);
            $result = $roleService->edit($id, $menu);

            return $this->ajax($result, $roleService);
        }

        $role = $roleService->model->find($id);

        return $this->view('admin.system.operate.role', compact('role'));
    }

    /**
     * 修改角色内容
     *
     * @param int $id
     * @param Request $request
     * @param RoleService $roleService
     * @Permission(identification="changeRole")
     * @return JsonResponse
     * @throws ValidationException
     */
    public function changeRole(int $id, Request $request, RoleService $roleService)
    {
        $role = $this->validate($request, [
            'status' => 'nullable|in:1,2',
        ],[
            'status.in' => '参数非法',
        ]);

        $result = $roleService->edit($id, $role);

        return $this->ajax($result, $roleService);
    }

    /**
     * 编辑角色权限
     *
     * @param $id
     * @param Request $request
     * @param PermissionService $permissionService
     * @param RoleService $roleService
     * @Permission(identification="rolePermission")
     * @return View|JsonResponse
     */
    public function rolePermission($id, Request $request, PermissionService $permissionService, RoleService $roleService)
    {
        $role = $roleService->model->find($id);

        if (empty($role)) {
            abort(404);
        }
        if ($request->isMethod('PUT')) {
            $permissionIds = $request->get('permissionIds', []);
            $result = $roleService->editPermission($role, $permissionIds);

            return $this->ajax($result, $permissionService);
        }
        $checkPermission = $role->permission->pluck('id')->toArray();
        $permissionTree = $permissionService->getList([], $checkPermission, true);

        return $this->view('admin.system.operate.rolePermission', compact('role', 'permissionTree', 'id'));
    }

    /**
     * 删除角色
     *
     * @param int $id
     * @param RoleService $roleService
     * @Permission(identification="delRole")
     * @return JsonResponse
     */
    public function delRole($id, RoleService $roleService)
    {
        $result = $roleService->delete($id);

        return $this->ajax($result, $roleService);
    }
}
