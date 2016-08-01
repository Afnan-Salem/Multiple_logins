<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * search role by name
     * @param $name
     * @return id of role having the passed name
     */
    public function search_role($name)
    {
        $role=Role::where('name', '=', $name)->first();
        return $role->id;
    }
}
