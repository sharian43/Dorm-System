<?php

namespace BusinessLogic;

use DataAccess;

require "../Data Access/DBController.php";
require "Ticket.php";

// Retreive the session variables set at login
session_start();

class QueueContoller
{

    private $mysqli;

    public function __construct()
    {
    }

    public function getDailyTickets()
    {
        $controller = new DataAccess\DBController;
        $message = $controller->ticketDisplay();
        $controller->closeConnection();
        return $message;
    }


    public function closeConnection()
    {
        $this->mysqli->close();
    }
}
