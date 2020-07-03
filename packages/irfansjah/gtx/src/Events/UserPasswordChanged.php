<?php

namespace Irfansjah\Gtx\Events;

use Illuminate\Queue\SerializesModels;

class UserPasswordChanged
{
    use SerializesModels;

    public $previous;


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
