<?php

namespace App\Events;

class EmailSendEvent extends Event
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }
}
