<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systemMenu = Menu::create([
            'pid' => 0, 'title' => '系统控制', 'icon' => 'icon pear-icon pear-icon-setting', 'href' => 'none', 'type' => 0, 'open_type' => '_iframe', 'level' => 1, 'status' => 1, 'sort' => 0,
        ]);
        Menu::insert([
            ['pid' => $systemMenu->id, 'title' => '菜单管理', 'icon' => 'icon pear-icon pear-icon-menu', 'href' => 'system/menu', 'type' => 1, 'open_type' => '_iframe', 'level' => 2, 'status' => 1, 'sort' => 10000, 'created_at' => $systemMenu->created_at],
            ['pid' => $systemMenu->id, 'title' => '管理员', 'icon' => 'icon pear-icon pear-icon-user', 'href' => 'system/admin', 'type' => 1, 'open_type' => '_iframe', 'level' => 2, 'status' => 1, 'sort' => 10000, 'created_at' => $systemMenu->created_at],
            ['pid' => $systemMenu->id, 'title' => '权限管理', 'icon' => 'icon pear-icon pear-icon-lock', 'href' => 'system/permission', 'type' => 1, 'open_type' => '_iframe', 'level' => 2, 'status' => 1, 'sort' => 10000, 'created_at' => $systemMenu->created_at],
            ['pid' => $systemMenu->id, 'title' => '角色管理', 'icon' => 'icon pear-icon pear-icon-setting', 'href' => 'system/role', 'type' => 1, 'open_type' => '_iframe', 'level' => 2, 'status' => 1, 'sort' => 10000, 'created_at' => $systemMenu->created_at],
        ]);

    }
}
