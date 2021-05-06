<?php

namespace App\Services;

use App\Common\Enum\HttpCode;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleService extends Service
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    /**
     * 通过角色id获取权限列表
     *
     * @param int|array $roleId
     *
     * @return array
     */
    public function getPermission($roleId): array
    {
        $roleIds = is_array($roleId) ? $roleId : func_get_args();
        $permissions = [];
        foreach ($roleIds as $roleId) {
            $role = $this->model->find($roleId);
            if ($role) {
                $rolePermissions = $role->first()->permission->toArray();
                foreach ($rolePermissions as $rolePermission) {
                    if (!isset($permissions[$rolePermission['id']])) {
                        $permissions[$rolePermission['id']] = $rolePermission;
                    }
                }
            }
        }

        return $permissions;
    }

    /**
     * 角色列表
     *
     * @param Request $request
     *
     * @return void
     */
    public function getList(Request $request)
    {
        return $this->model->paginate($request->get('limit', 10));
    }

    /**
     * 可用角色列表
     *
     * @return mixed
     */
    public function getEnableList()
    {
        return $this->model->where('status', 1)->get();
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
        $menu = $this->model->firstOrNew(compact('id'));
        foreach ($data as $key => $value) {
            $menu->$key = $value;
        }

        $menu->save();

        return true;
    }

    /**
     * 编辑角色权限
     *
     * @param Permission $permission
     * @param $permissionIds
     * @return bool
     */
    public function editPermission(Role $role, $permissionIds)
    {
        $permissions = $role->permission->pluck('id')->toArray();
        $addPermissions = array_diff($permissionIds, $permissions);
        $deletePermissions = array_diff($permissions, $permissionIds);

        foreach ($addPermissions as $addPermission) {
            $role->rolePermission()->insert([
                'role_id' => $role->id,
                'permission_id' => $addPermission,
            ]);
        }
        $role->rolePermission()->whereIn('permission_id', $deletePermissions)->delete();

        return true;
    }

    /**
     * 删除
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id)
    {
        if ($id == 1) {
            $this->error = '禁止删除超级管理员';
            $this->httpCode = HttpCode::FORBIDDEN;
            return false;
        }

        $menu = $this->model->find($id);
        if (!empty($menu)) {
            $this->error = '角色不存在';
            $this->httpCode = HttpCode::NOT_FOUND;
            return false;
        }
        $menu->status = 0;
        $menu->save();

        return true;
    }
}
