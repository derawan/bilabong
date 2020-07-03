<?php

namespace Irfansjah\Gtx\Events;

use Illuminate\Queue\SerializesModels;

class UserDeleted
{
    use SerializesModels;

    /**
     * The deleted user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }


}
