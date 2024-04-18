<?php

namespace BusinessLogic;

use DataAccess\DBController;

session_start();

use DataAccess;
use Security;

require "../Data Access/DBController.php";
require "Machine.php";
require "Timeslot.php";


class AuthenticateTicketContoller
{
    public function __construct()
    {
        $auth = new Security\Authenticator();
        if (!$auth->authenticateResident()) {
            header("Location: http://localhost/dorm-System/php/presentation");
            exit;
        }
    }

    public function authenticateReservation()
    {
        $controller = new DataAccess\DBController;
        $tickets = $controller->fetchResidentTickets();
        $controller->closeConnection();
        return $tickets;
    }

    public function authenticateAllReservations()
    {
        $controller = new DataAccess\DBController;
        $tickets = $controller->fetchLaundryTickets();
        $controller->closeConnection();
        return $tickets;
    }
}
