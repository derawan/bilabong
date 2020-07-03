<?php

namespace Irfansjah\Gtx\Models;

use Illuminate\Database\Eloquent\Model;

class EntityCategoryType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type','multilevel', 'system'
    ];
}
