<?php

/*
 * UF AltPermissions Sprinkle
 *
 * @author    Louis Charette
 * @copyright Copyright (c) 2018 Louis Charette
 * @link      https://github.com/lcharette/UF_AltPermissions
 * @license   https://github.com/lcharette/UF_AltPermissions/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\AltPermissions\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

/**
 * Role Class.
 *
 * Represents a role, which aggregates permissions and to which a user can be assigned.
 *
 * @author Louis Charette (https://github.com/lcharette)
 *
 * @property string slug
 * @property string name
 * @property string description
 */
class Role extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'alt_roles';

    protected $fillable = [
        'seeker',
        'name',
        'description',
        'lock',
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Delete this role from the database, removing associations with permissions and users.
     */
    public function delete()
    {
        // Remove all permission associations
        $this->permissions()->detach();

        // Remove all user associated to this role. We delete the whole `auth` entry
        $this->auth()->delete();

        // Delete the role
        $result = parent::delete();

        return $result;
    }

    /**
     * Get a list of permissions assigned to this role.
     */
    public function permissions()
    {
        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsToMany($classMapper->getClassMapping('altPermission'), 'alt_permission_roles', 'role_id', 'permission_id')->withTimestamps();
    }

    public function auth($seeker = '')
    {
        if ($seeker != '') {
            $seekerClass = static::$ci->acl->getSeekerModel($seeker);

            return $this->hasMany('UserFrosting\Sprinkle\AltPermissions\Database\Models\Auth')->where('seeker_type', $seekerClass)->get();
        } else {
            return $this->hasMany('UserFrosting\Sprinkle\AltPermissions\Database\Models\Auth');
        }
    }

    /**
     * Model's getter.
     */

    /**
     * getRoute function.
     * Helper function for when the $ci is not directly avaiable.
     *
     * @param string $routeName
     *
     * @return Route for the designated route name
     */
    public function getRoute($routeName)
    {
        $data = $this->toArray();

        // Need to translate the seeker back into it's key value
        $data['seeker'] = static::$ci->acl->getSeekerKey($this->seeker);

        return static::$ci->router->pathFor($routeName, $data);
    }

    /**
     * Model's Scope.
     */

    /**
     * Query scope to get all roles assigned to a specific seeker.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $seeker
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSeeker($query, $seeker)
    {
        $seekerClass = static::$ci->acl->getSeekerModel($seeker);

        return $query->where('seeker', $seekerClass);
    }
}
