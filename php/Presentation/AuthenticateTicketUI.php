<?php

namespace Presentation;

use Security;
use Users;
use BusinessLogic;


require "../Business Logic/AuthenticateTicketController.php";
require "../Users/Resident.php";

class AuthenticateTicketUI
{
    public function __construct()
    {
    }

    public function authenticateTicket()
    {
        $controller = new BusinessLogic\AuthenticateTicketContoller();
        if ($_SESSION['role'] == 'resident') {
            return $controller->authenticateReservation();
        }
        return $controller->authenticateAllReservations();
    }
}
