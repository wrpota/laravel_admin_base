<?php

namespace App\Services;

use App\Common\Enum\HttpCode;
use App\Models\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminService extends Service
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Admin::class;
    }

    /**
     * @param string $name
     * @param string $password
     * @param bool $remember
     * @return bool
     */
    public function login(string $name, string $password, bool $remember)
    {
        $admin = Admin::where('username', $name)->first();

        if (!$admin) {
            $this->error = '账号或密码错误';
            $this->httpCode = HttpCode::BAD_REQUEST;
            return false;
        }
        if ($admin->status != Admin::ACTIVE_STATUS) {
            $this->error = '该账号未激活';
            $this->httpCode = HttpCode::FORBIDDEN;
            return false;
        }
        $re = password_verify($password, $admin->password);
        if (!$re) {
            $this->error = '账号或密码错误';
            $this->httpCode = HttpCode::BAD_REQUEST;
            return false;
        }
        Auth::guard('admin')->login($admin, $remember);

        return true;
    }

    /**
     * 管理员列表
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getList(Request $request)
    {
        $model = $this->model->whereBetween('status', [1, 2]);
        if ($request->get('id')) {
            $model->where('id', $request->get('id'));
        }
        if ($request->get('username')) {
            $model->where('username', 'LIKE', "%{$request->get('username')}%");
        }

        return $model->paginate(10);
    }

    /**
     * 编辑
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data): bool
    {
        if ($id == 1 && empty($data['roleIds'])) {
            $this->error = '管理员账号不可无角色';
            $this->httpCode = HttpCode::FORBIDDEN;
            return false;
        }

        $admin = $this->model->firstOrNew(compact('id'));

        $admin->username = $data['username'];
        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }
        $admin->save();

        $roles = $admin->role->pluck('id')->toArray();
        $addRoles = array_diff($data['roleIds'], $roles);
        $deleteRoles = array_diff($roles, $data['roleIds']);

        foreach ($addRoles as $addRole) {
            $admin->adminRole()->insert([
                'role_id' => $addRole,
                'admin_Id' => $admin->id,
            ]);
        }
        $admin->adminRole()->whereIn('role_id', $deleteRoles)->delete();

        return true;
    }

    /**
     * 修改用户状态
     *
     * @param int $id
     * @param int $status
     *
     * @return bool
     */
    public function changeStatus(int $id, int $status)
    {
        $admin = $this->model->find($id);
        if (empty($admin)) {
            $this->error = '账号不存在';
            $this->httpCode = HttpCode::NOT_FOUND;
            return false;
        }
        $admin = $this->model->find($id);
        if ($id == 1 && $status != 1) {
            $this->error = '管理员账号不可执行此操作';
            $this->httpCode = HttpCode::FORBIDDEN;
            return false;
        }
        $admin->status = $status;

        return $admin->save();
    }

    /**
     * 获取用户菜单
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function menu(): \Illuminate\Http\JsonResponse
    {
        $permissions = $this->getPermission();
        $permissionService = app(PermissionService::class);
        $menus= $permissionService->getMenu($permissions);
        $menuService = app(MenuService::class);

        return response()->json($menuService->getTreeList(array_keys($menus)));
    }

    /**
     * 获取当前用户权限列表
     *
     * @return array
     */
    public function getPermission(): array
    {
        $id = Auth::guard('admin')->id();
        $admin = $this->model->find($id);
        $roles = $admin->role->toArray();
        $roleService = app(RoleService::class);

        return $roleService->getPermission($roles);
    }
}
