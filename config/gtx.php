<?php

return [
    "missing_image" => [
        "avatar" => "images/noimage.png"
    ],
    "user_map" => [
        "App\Models\Member" => "member",
        "App\Models\User" => "user",
    ],
    "admin"=>[
        "enable_password_reset" => true,
        "enable_registration" => true
    ],
    "member"=>[
        "enable" => true,
        "enable_password_reset" => true,
        "enable_registration" => true
    ]

];
