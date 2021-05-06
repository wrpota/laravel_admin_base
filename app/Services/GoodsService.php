<?php
/**
 * Created by PhpStorm.
 * User: alina
 * DateTime: 2021/2/7 17:38
 */

namespace App\Services;

use App\Models\Goods;
use App\Models\GoodsCategory;

class GoodsService extends Service
{
    /**
     * 编辑分类
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function editcate(int $id, $data)
    {
        $bannerCategory = app(GoodsCategory::class);
        $category = $bannerCategory->firstOrNew(compact('id'));

        foreach ($data as $key => $value) {
            $category->$key = $value;
        }

        return $category->save();
    }

    /**
     * 删除分类
     *
     * @param int $id
     */
    public function delCategory(int $id)
    {
        return GoodsCategory::where('id', $id)->delete();
    }

    /**
     * 添加分类
     * @param $data
     */
    public function addCategory($data)
    {
        $res = GoodsCategory::create($data);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * 添加
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        $res = Goods::create($data);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * 编辑
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function edit(int $id, $data)
    {
        $banner = app(Goods::class)->firstOrNew(compact('id'));

        foreach ($data as $key => $value) {
            $banner->$key = $value;
        }

        return $banner->save();
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     */
    public function del(int $id)
    {
        return Goods::where('id', $id)->delete();
    }
}
