<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionMenu;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert(
            [
                [
                    "id" => 1,
                    "pid" => 0,
                    "name" => "系统控制",
                    "identification" => "system",
                    "remark" => "",
                    "level" => 1,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 2,
                    "pid" => 1,
                    "name" => "管理员",
                    "identification" => "system.admin",
                    "remark" => "",
                    "level" => 2,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 3,
                    "pid" => 2,
                    "name" => "修改管理员状态",
                    "identification" => "system.changeadmin",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 4,
                    "pid" => 2,
                    "name" => "编辑管理员",
                    "identification" => "system.editadmin",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 5,
                    "pid" => 2,
                    "name" => "删除管理员",
                    "identification" => "system.deladmin",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 6,
                    "pid" => 1,
                    "name" => "菜单管理",
                    "identification" => "system.menu",
                    "remark" => "",
                    "level" => 2,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 7,
                    "pid" => 6,
                    "name" => "编辑菜单",
                    "identification" => "system.editmenu",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 8,
                    "pid" => 6,
                    "name" => "删除菜单",
                    "identification" => "system.delmenu",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 9,
                    "pid" => 6,
                    "name" => "修改菜单状态排序",
                    "identification" => "system.changemenu",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 10,
                    "pid" => 1,
                    "name" => "权限管理",
                    "identification" => "system.permission",
                    "remark" => "",
                    "level" => 2,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 11,
                    "pid" => 10,
                    "name" => "修改权限",
                    "identification" => "system.editpermission",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 12,
                    "pid" => 10,
                    "name" => "编辑权限菜单",
                    "identification" => "system.permissionmenu",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 13,
                    "pid" => 10,
                    "name" => "删除权限",
                    "identification" => "system.delpermission",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 14,
                    "pid" => 10,
                    "name" => "切换权限状态",
                    "identification" => "system.changepermission",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 15,
                    "pid" => 1,
                    "name" => "角色管理",
                    "identification" => "system.role",
                    "remark" => "",
                    "level" => 2,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 16,
                    "pid" => 15,
                    "name" => "修改角色",
                    "identification" => "system.editrole",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 17,
                    "pid" => 15,
                    "name" => "切换角色状态",
                    "identification" => "system.changerole",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 18,
                    "pid" => 15,
                    "name" => "编辑角色权限",
                    "identification" => "system.rolepermission",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ],
                [
                    "id" => 19,
                    "pid" => 15,
                    "name" => "删除角色",
                    "identification" => "system.delrole",
                    "remark" => "",
                    "level" => 3,
                    "status" => 1,
                    "sort" => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                ]
            ]
        );

        PermissionMenu::insert(
            [
                [
                    "id" => 1,
                    "permission_id" => 10,
                    "menu_id" => 1
                ],
                [
                    "id" => 2,
                    "permission_id" => 10,
                    "menu_id" => 4
                ],
                [
                    "id" => 3,
                    "permission_id" => 15,
                    "menu_id" => 1
                ],
                [
                    "id" => 4,
                    "permission_id" => 15,
                    "menu_id" => 5
                ],
                [
                    "id" => 5,
                    "permission_id" => 6,
                    "menu_id" => 1
                ],
                [
                    "id" => 6,
                    "permission_id" => 6,
                    "menu_id" => 2
                ],
                [
                    "id" => 7,
                    "permission_id" => 2,
                    "menu_id" => 1
                ],
                [
                    "id" => 8,
                    "permission_id" => 2,
                    "menu_id" => 3
                ]
            ]
        );
    }
}

