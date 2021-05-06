<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pid',
        'title',
        'identification',
        'remark',
        'sort',
    ];

    /**
     * 权限菜单
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function menu()
    {
        return $this->HasManyThrough(
            Menu::class,
            PermissionMenu::class,
            'permission_id',
            'id',
            'id',
            'menu_id'
        );
    }

    /**
     * 权限菜单关联表
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissionMenu()
    {
        return $this->hasMany(PermissionMenu::class, 'permission_id');
    }
}
