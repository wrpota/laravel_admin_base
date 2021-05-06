<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'remark',
        'status',
    ];

    /**
     * 角色权限
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function permission()
    {
        return $this->HasManyThrough(
            Permission::class,
            RolePermission::class,
            'role_id',
            'id',
            'id',
            'permission_id',
        );
    }

    /**
     * 角色权限关联表
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rolePermission()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }

}
