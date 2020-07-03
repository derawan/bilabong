<?php

namespace Irfansjah\Gtx\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value', 'group','options'
    ];
}
