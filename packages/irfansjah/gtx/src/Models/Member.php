<?php

namespace Irfansjah\Gtx\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
/* Use Laravel Media Library For Handling Avatar */
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\File;
use Irfansjah\Gtx\Traits\UserWithAvatar;

class Member extends User
{

}
