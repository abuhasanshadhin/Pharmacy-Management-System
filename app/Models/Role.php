<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    const SUPER_ADMIN_ROLE_NAME = 'Super Admin';

    public function scopeExceptSuperAdmin($query)
    {
        return $query->where('name', '!=', self::SUPER_ADMIN_ROLE_NAME);
    }

    /**
     * The permissions that belong to the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * Get super admin role id
     *
     * @return int
     */
    public static function superAdminRoleId()
    {
        return self::where('name', self::SUPER_ADMIN_ROLE_NAME)->value('id');
    }
}
