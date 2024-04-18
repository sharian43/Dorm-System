<?php

namespace BusinessLogic;

use DataAccess;

class Ticket
{
    public $ticketID;
    public $time;

    public function __construct($ticketID, $time)
    {
        $this->ticketID = $ticketID;
        $this->time = $time;
    }
    public function getTicketID()
    {
        return $this->ticketID;
    }
    public function getTime()
    {
        return $this->time;
    }
}
