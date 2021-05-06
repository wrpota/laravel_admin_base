<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolePermission::insert(
            [
                [
                    "id" => 1,
                    "role_id" => 1,
                    "permission_id" => 1
                ],
                [
                    "id" => 2,
                    "role_id" => 1,
                    "permission_id" => 10
                ],
                [
                    "id" => 3,
                    "role_id" => 1,
                    "permission_id" => 14
                ],
                [
                    "id" => 4,
                    "role_id" => 1,
                    "permission_id" => 13
                ],
                [
                    "id" => 5,
                    "role_id" => 1,
                    "permission_id" => 12
                ],
                [
                    "id" => 6,
                    "role_id" => 1,
                    "permission_id" => 11
                ],
                [
                    "id" => 7,
                    "role_id" => 1,
                    "permission_id" => 15
                ],
                [
                    "id" => 8,
                    "role_id" => 1,
                    "permission_id" => 19
                ],
                [
                    "id" => 9,
                    "role_id" => 1,
                    "permission_id" => 18
                ],
                [
                    "id" => 10,
                    "role_id" => 1,
                    "permission_id" => 17
                ],
                [
                    "id" => 11,
                    "role_id" => 1,
                    "permission_id" => 16
                ],
                [
                    "id" => 12,
                    "role_id" => 1,
                    "permission_id" => 6
                ],
                [
                    "id" => 13,
                    "role_id" => 1,
                    "permission_id" => 9
                ],
                [
                    "id" => 14,
                    "role_id" => 1,
                    "permission_id" => 8
                ],
                [
                    "id" => 15,
                    "role_id" => 1,
                    "permission_id" => 7
                ],
                [
                    "id" => 16,
                    "role_id" => 1,
                    "permission_id" => 2
                ],
                [
                    "id" => 17,
                    "role_id" => 1,
                    "permission_id" => 5
                ],
                [
                    "id" => 18,
                    "role_id" => 1,
                    "permission_id" => 4
                ],
                [
                    "id" => 19,
                    "role_id" => 1,
                    "permission_id" => 3
                ]
            ]
        );
    }
}

