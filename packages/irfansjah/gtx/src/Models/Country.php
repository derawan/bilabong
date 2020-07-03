<?php

namespace Irfansjah\Gtx\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_code', 'country_name'
        //,'iso_alpha2_code', 'iso_alpha3_code','iso_numeric_code'
    ];
}
