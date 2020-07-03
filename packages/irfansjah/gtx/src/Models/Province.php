<?php

namespace Irfansjah\Gtx\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'province_code',
        'province_alt_code'.
        'province_name'
    ];
}
