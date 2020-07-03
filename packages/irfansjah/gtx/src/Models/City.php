<?php

namespace Irfansjah\Gtx\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_code',
        'city_alt_code'.
        'city_name',
        'city_alt_name',
        'city_type',
        'province_id'
    ];
}
