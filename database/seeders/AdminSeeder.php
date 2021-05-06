<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Article;
use App\Models\Banner;
use App\Models\BannerCategory;
use App\Models\FeedBack;
use App\Models\Goods;
use App\Models\GoodsCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::insert(
            [
                [
                    'username' => 'admin',
                    'password' => Hash::make('L8hNAhcHEPjXEHHKS')
                ],
                [
                    'username' => 'alina',
                    'password' => Hash::make('123456')
                ]
            ]
        );
        AdminRole::insert(
            [
                [
                    'admin_id' => 1,
                    'role_id' => 1,
                ],
                [
                    'admin_id' => 2,
                    'role_id' => 1,
                ]
            ]
        );
    }
}
