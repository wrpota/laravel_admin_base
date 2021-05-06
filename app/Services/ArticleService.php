<?php
/**
 * Created by PhpStorm.
 * User: alina
 * DateTime: 2021/2/5 14:39
 */

namespace App\Services;


use App\Models\Article;

class ArticleService extends Service
{
    /**
     * 获取列表
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        $res = Article::create($data);
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
        $banner = app(Article::class)->firstOrNew(compact('id'));

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
        return Article::where('id', $id)->delete();
    }
}
