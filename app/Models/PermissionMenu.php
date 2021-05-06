<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionMenu extends Model
{
    use HasFactory;

    protected $table = 'permission_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_id',
        'menu_id',
    ];

    public function menu()
    {
        return $this->hasOne(Menu::class, 'id', 'menu_id');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }
}
