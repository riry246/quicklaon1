<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function userAttrSets()
    {
        return $this->hasMany(UserAttrSet::class)->with('userAttrFields');
    }
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    // Add an event listener to delete permissions when a group is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($group) {
            // Delete all permissions associated with the group
            $group->permissions()->delete();
        });
    }
}
