<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserRole extends Model
{

    protected $table = 'user_roles';
    protected $fillable = ['user_id', 'role_id'];

    /**
     * Получить имя роли по ID пользователя.
     *
     * @param int $userId
     * @return string|null
     */
    public static function getRoleNameByUserId(int $userId): ?string
    {
        return DB::table('users')
            ->join('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('users.id', $userId)
            ->value('roles.name');
    }
}
