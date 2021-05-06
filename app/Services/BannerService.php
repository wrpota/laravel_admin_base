<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\BannerCategory;

class BannerService extends Service
{
    /**
     * 编辑banner分类
     *
     * @param int $id
     * @param $data
     * @return bool
     */
    public function editcate(int $id, $data)
    {
        $bannerCategory = app(BannerCategory::class);
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
        return BannerCategory::where('id', $id)->delete();
    }

    /**
     * 添加分类
     * @param $data
     */
    public function addCategory($data)
    {
        $res = BannerCategory::create($data);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * 添加banner
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        $res = Banner::create($data);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * 编辑banner
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function edit(int $id, $data)
    {
        $banner = app(Banner::class)->firstOrNew(compact('id'));

        foreach ($data as $key => $value) {
            $banner->$key = $value;
        }

        return $banner->save();
    }

    /**
     * 删除banner
     * @param int $id
     * @return mixed
     */
    public function del(int $id)
    {
        return Banner::where('id', $id)->delete();
    }
}
