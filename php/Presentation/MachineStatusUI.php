<?php

namespace Presentation;

use BusinessLogic\Machine;
use BusinessLogic\MachineStatusContoller;
use Users;
use BusinessLogic;

require "../Business Logic/MachineStatusController.php";

class MachineStatusUI
{
    private $reservations;

    public function __construct()
    {
    }

    public function fetchMachineStatuses()
    {
        $controller = new BusinessLogic\MachineStatusContoller();
        return $controller->fetchStatuses();
    }

    public function changeMachineStatus($machineKey, $status)
    {
        $controller = new BusinessLogic\MachineStatusContoller();
        return $controller->changeStatus($machineKey, $status);
    }
}

if (isset($_POST['machine'])) {
    $stat = new MachineStatusUI();
    $status = $stat->changeMachineStatus($_POST['machine'], $_POST['status']);
    echo $status;
    exit();
}
