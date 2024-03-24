<?php

namespace Presentation;

use BusinessLogic;
use Users;

require "../Business Logic/TimeslotController.php";
require "../Users/Resident.php";

class TimeslotUI
{
    private $machineNum;
    function __construct()
    {
        if (isset($_POST['machineNum'])) {
            $machineNum = $_POST['machineNum'];
            $this->machineNum = $machineNum;
        }
    }

    public function showMachineStatus($machineKey)
    {
        $controller = new BusinessLogic\TimeslotController();
        return $controller->fetchStatus($machineKey);
    }
    public function removeTimePassed($machineKey, $timeslot, $selectedDay)
    {
        $controller = new BusinessLogic\TimeslotController();
        return $controller->timeslotRemover($machineKey, $timeslot, $selectedDay);
    }
    public function assignTime($machineKey, $timeslot, $selectedDay)
    {
        $controller = new BusinessLogic\TimeslotController();
        $user = new Users\Resident("4567", "Something", "Something");
        return $controller->assignTimeslot($machineKey, $timeslot, $selectedDay, $user);
    }

    public function reservationsForDay($selectedDay)
    {
        $controller = new BusinessLogic\TimeslotController();
        return $controller->dailyReservations($selectedDay);
    }

    public function reservationsForcheck($selectedDay)
    {
        $controller = new BusinessLogic\TimeslotController();
        return $controller->checkingReservations($selectedDay);
    }
}

$handle =  new TimeslotUI();

if (isset($_POST['timeslot'], $_POST['machine'], $_POST['selectedDay'])) {
    echo $handle->assignTime($_POST['machine'], $_POST['timeslot'], $_POST['selectedDay']);
} elseif (isset($_POST['selectedDay'])) {
    echo $handle->reservationsForDay($_POST['selectedDay']);
}
