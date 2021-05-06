<?php
/**
 * Created by PhpStorm.
 * User: alina
 * DateTime: 2021/2/1 11:19
 */

namespace App\Services;


use App\Models\FeedBack;

class FeedBackService extends Service
{
    /**
     * 回复
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function reply(int $id, $data)
    {
        $feedback = app(FeedBack::class)->firstOrNew(compact('id'));
        foreach ($data as $key => $value) {
            $feedback->$key = $value;
        }

        return $feedback->save();
    }

    /**
     * 获取详情内容
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        $feedback = app(FeedBack::class)->firstOrNew(compact('id'));
        return $feedback;
    }
}
