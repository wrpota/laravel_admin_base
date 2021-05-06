<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    const ACTIVE_STATUS = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * 角色权限
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function role()
    {
        return $this->HasManyThrough(
            Role::class,
            AdminRole::class,
            'admin_id',
            'id',
            'id',
            'role_id',
        );
    }

    /**
     * 管理员角色关联表
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adminRole()
    {
        return $this->hasMany(AdminRole::class, 'admin_id');
    }
}
