<?php
/**
 * Created by PhpStorm.
 * User: alina
 * DateTime: 2021/2/2 11:56
 */

namespace App\Services;


use App\Models\User;

class UserService extends Service
{
    /**
     * 修改用户状态
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function change(int $id, $data)
    {
        $user = app(User::class)->firstOrNew(compact('id'));

        foreach ($data as $key => $value) {
            $user->$key = $value;
        }

        return $user->save();
    }

    /**
     * 新增用户
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        $res = User::create($data);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * 编辑用户
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function edit(int $id, $data)
    {
        $banner = app(User::class)->firstOrNew(compact('id'));

        foreach ($data as $key => $value) {
            $banner->$key = $value;
        }

        return $banner->save();
    }
}
