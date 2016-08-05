<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Orchestra extends Model
{
    //
    public function create_record($id,$name)
    {
        DB::insert('insert into orchestra_officers (officer_id,orchestra_name) values(?,?)',[$id,$name]);

    }
}
