<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;

    protected $table = 'admin_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'role_id',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
