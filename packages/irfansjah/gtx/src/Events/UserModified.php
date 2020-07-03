<?php

namespace Irfansjah\Gtx\Events;

use Illuminate\Queue\SerializesModels;

class UserModified
{
    use SerializesModels;

    public $previous;
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
    public function __construct($user, $previous)
    {
        $this->user = $user;
        $this->previous = $previous;
    }
}
