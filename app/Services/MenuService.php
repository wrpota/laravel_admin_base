<?php

namespace App\Services;

use App\Common\Enum\HttpCode;
use App\Models\Menu;

class MenuService extends TreeModelService
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Menu::class;
    }

    /**
     * 编辑菜单
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
        if (isset($data['pid'])) {
            $menu->level = ($this->model->find($data['pid'])->level ?? 0) + 1;
        }

        $menu->save();

        return true;
    }

    /**
     * 删除菜单
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id)
    {
        $menu = $this->model->find($id);
        if (empty($menu)) {
            $this->error = '菜单不存在';
            $this->httpCode = HttpCode::NOT_FOUND;
            return false;
        }
        $menu->status = 0;
        $menu->save();

        return true;
    }
}
