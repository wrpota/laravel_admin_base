<?php

namespace App\Services;

use App\Common\Enum\HttpCode;
use App\Models\Permission;
use Doctrine\Common\Annotations\AnnotationReader;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class PermissionService extends TreeModelService
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Permission::class;
    }

    /**
     * 权限换成文件
     *
     * @return string
     */
    public function getCachedPermissionPath()
    {
        return app()->bootstrapPath('cache/permission.php');
    }

    /**
     * 处理权限
     *
     * @throws \ReflectionException
     */
    public function handle()
    {
        $rbacNamespaces = config('rbac.namespaces');

        $routeControllers = [];
        //获取所有有路由访问的控制器/方法
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (array_key_exists('controller', $action)) {
                $needRbac = false;
                foreach ($rbacNamespaces as $rbacNamespace) {
                    if (Str::startsWith($action['controller'], $rbacNamespace)) {
                        $needRbac = true;
                    }
                }
                if ($needRbac) {
                    $_controller = explode('@', $action['controller']);
                    $routeControllers[$_controller[0]][] = $_controller[1];
                }
            }
        }
        //处理权限信息
        $permissions = [];
        foreach ($routeControllers as $controller => $actions) {
            $controllerClass = new ReflectionClass($controller);
            foreach ($actions as $action) {
                $reflectionMethod = $controllerClass->getMethod($action);
                $permission = $this->getPermissionByReflectionMethod($reflectionMethod);
                $permissions[$controller . '@' . $action] = $permission;
            }
        }

        file_put_contents(
            $this->getCachedPermissionPath(),
            '<?php' . PHP_EOL . 'return ' .
            var_export($permissions, true) .
            ';'
        );
    }

    /**
     * 获取方法反射获取对应权限
     *
     * @param ReflectionMethod $reflectionMethod
     *
     * @return array
     * @throws \ReflectionException
     */
    private function getPermissionByReflectionMethod(ReflectionMethod $reflectionMethod): array
    {
        $annotationReader = app(AnnotationReader::class);

        $reflectionController = new ReflectionClass($reflectionMethod->class);
        $permissionController = $annotationReader->getClassAnnotation($reflectionController, \App\Attributes\Annotation\Permission::class);
        if (!empty($permissionController)) {
            $controllerPermission = $permissionController->identification;
        } else {
            $namespacesChunks = explode('\\', $reflectionMethod->class);
            $className = end($namespacesChunks);
            $controllerPermission = Str::beforeLast($className, "Controller");
        }

        $permission = $annotationReader->getMethodAnnotation($reflectionMethod, \App\Attributes\Annotation\Permission::class);
        if (!empty($permission)) {
            return [
                'identification' => Str::lower($controllerPermission . '.' . $permission->identification),
                'method' => $permission->method
            ];
        }

        return ['identification' => Str::lower($controllerPermission . '.' . $reflectionMethod->getName()), 'method' => 'ALL'];
    }

    /**
     * 通过方法名获取对应权限名
     *
     * @param array $action
     * @return mixed|string
     * @throws \ReflectionException
     */
    public function getPermissionByAction(array $action)
    {
        if (is_file($cachePermissionFile = $this->getCachedPermissionPath())) {
            $permissions = require $cachePermissionFile;
            if (isset($permissions[$action['controller']])) {
                return $permissions[$action['controller']];
            }
        }
        $controllerAction = explode('@', $action['controller']);
        return $this->getPermissionByReflectionMethod(new ReflectionMethod($controllerAction[0], $controllerAction[1]));
    }

    /**
     * 通过权限获取菜单列表
     *
     * @param int|array $permissionId
     *
     * @return array
     */
    public function getMenu($permissionId)
    {
        $permissionIds = is_array($permissionId) ? $permissionId : func_get_args();
        $menus = [];
        foreach ($permissionIds as $permissionId) {
            $permission = $this->model->find($permissionId);
            if ($permission) {
                $permissionMenus = $permission->first()->menu->where('status', 1)->toArray();
                foreach ($permissionMenus as $permissionMenu) {
                    if (!isset($menus[$permissionMenu['id']])) {
                        $menus[$permissionMenu['id']] = $permissionMenu;
                    }
                }
            }
        }

        return $menus;
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
        $permission = $this->model->firstOrNew(compact('id'));
        $data['remark'] = Arr::get($data, 'remark', '') ?? '';
        foreach ($data as $key => $value) {
            $permission->$key = $value;
        }

        if (isset($data['pid'])) {
            $permission->level = ($this->model->find($data['pid'])->level ?? 0) + 1;
        }

        $permission->save();

        return true;
    }

    /**
     * 编辑权限菜单
     *
     * @param Permission $permission
     * @param $menuIds
     * @return bool
     */
    public function editMenu(Permission $permission, $menuIds)
    {
        $menus = $permission->menu->pluck('id')->toArray();
        $addMenus = array_diff($menuIds, $menus);
        $deleteMenus = array_diff($menus, $menuIds);
        foreach ($addMenus as $addMenu) {
            $permission->permissionMenu()->insert([
                'permission_id' => $permission->id,
                'menu_id' => $addMenu,
            ]);
        }
        $permission->permissionMenu()->whereIn('menu_id', $deleteMenus)->delete();

        return true;
    }

    /**
     * 删除权限
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id)
    {
        $permission = $this->model->find($id);
        if (!empty($menu)) {
            $this->error = '权限不存在';
            $this->httpCode = HttpCode::NOT_FOUND;
            return false;
        }
        $permission->status = 0;
        $permission->save();

        return true;
    }
}
