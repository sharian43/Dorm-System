<?php

namespace BusinessLogic;

use DataAccess\DBController;

session_start();

use DataAccess;
use Security;

require "../Data Access/DBController.php";
require "Machine.php";
require "Timeslot.php";


class CancelContoller
{
    public function __construct()
    {
    }
    public function fetchSlots()
    {
        $controller = new DataAccess\DBController;
        $timearray = $controller->getCancelSlots();
        $controller->closeConnection();
        return $timearray;
    }

    public function removeReservation($user, $timeslot, $selectedDay, $machine)
    {
        $ts = new Timeslot($selectedDay, $machine, $timeslot, $user);
        $auth = new Security\Authenticator();
        if (!$auth->authenticateResident()) {
            header("Location: http://localhost/dorm-System/php/presentation");
            exit;
        }
        $db = new DataAccess\DBController;
        $message = $db->removeTimeslot($machine, $timeslot, $selectedDay, $user);
        $db->closeConnection();
        return $message;
    }
}
