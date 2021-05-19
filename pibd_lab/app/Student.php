<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded=[];

    protected $casts=['paid'=>'boolean', 'age'=>'integer'];
}
