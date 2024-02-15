<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRolesTrait
{
    /**
     * The roles that belong to the HasRoles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Assign roles to the HasRoles
     *
     * @param mixed ...$roles
     * @return void
     */
    public function assignRole(...$roles)
    {
        $roleIds = $this->getRoleIds($roles);

        $this->roles()->attach($roleIds);
    }

    /**
     * Sync roles to the HasRoles
     *
     * @param mixed ...$roles
     * @return void
     */
    public function syncRoles(...$roles)
    {
        $roleIds = $this->getRoleIds($roles);

        $this->roles()->sync($roleIds);
    }

    /**
     * Revoke roles to the HasRoles
     *
     * @param mixed ...$roles
     * @return void
     */
    public function revokeRoles(...$roles)
    {
        $roleIds = $this->getRoleIds($roles);

        $this->roles()->detach($roleIds);
    }

    /**
     * Get role id collection
     *
     * @param mixed ...$roles
     * @return array
     */
    public function getRoleIds(...$roles)
    {
        $roleIds = [];

        foreach ($roles as $role) {
            if ($role instanceof Role) {
                $roleIds[] = $role->id;
            } else if (is_numeric($role)) {
                $roleIds[] = $role;
            }
        }

        return $roleIds;
    }
}
