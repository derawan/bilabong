<?php

namespace Irfansjah\Gtx\Traits;

use Illuminate\Support\Facades\File;

trait UserWithAvatar {


    public function getAvatar($thumb = true){
        $img = $this->getMedia('avatars')->first();
        if ($thumb)
            return $img? $this->getMedia('avatars')->last()->getUrl('thumb') : asset(config("gtx.missing_image.avatar"));
        else
            return $img? $this->getMedia('avatars')->last()->getUrl() : asset(config("gtx.missing_image.avatar"));
    }

    public function setAvatarFromRequest($param) {
        $this->addMediaFromRequest($param)->toMediaCollection('avatars','avatar');
        $allmedia = $this->getMedia('avatars')->all();
        $recent = $this->getMedia('avatars')->last();
        foreach($allmedia as $value) {
            if ($value->id != $recent->id) {
                File::deleteDirectory(pathinfo($value->getPath())["dirname"]);
                $value->delete();
            }
        }
    }
}
