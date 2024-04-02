<?php

namespace Presentation;

use Security;
use Users;
use BusinessLogic;


require "../Business Logic/CancelController.php";
require "../Users/Resident.php";

class CancelUI
{
    public function __construct()
    {
    }

    public function cancelReservationSlots()
    {
        $controller = new BusinessLogic\CancelContoller();
        return $controller->fetchSlots();
    }

    public function cancelReservation($timeslot, $selectedDay, $machine)
    {
        $controller = new BusinessLogic\CancelContoller();
        $username = $_SESSION['username'];
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
        $user = new Users\Resident($username, $firstname, $lastname);
        return $controller->removeReservation($user, $timeslot, $selectedDay, $machine);
    }
}

$handle = new CancelUI();
if (isset($_POST['time'], $_POST['machine'], $_POST['day'])) {
    echo $handle->cancelReservation($_POST['time'], $_POST['day'], $_POST['machine']);
}
