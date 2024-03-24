<?php

namespace BusinessLogic;

use DataAccess;

require "../Data Access/DBController.php";
require "Machine.php";

// Retreive the session variables set at login
session_start();

class MachineStatusContoller
{
    private $mysqli;

    public function __construct()
    {
    }

    public function fetchStatuses()
    {
        $controller = new DataAccess\DBController;
        $message = $controller->fetchStats();
        $controller->closeConnection();
        return $message;
    }

    public function changeStatus($machineKey, $status)
    {
        $controller = new DataAccess\DBController;
        $machine = new Machine($machineKey, $status);
        $message = $controller->updateMachineStatus($machine->getMachineID());
        $controller->closeConnection();
        return $message;
    }

    public function closeConnection()
    {
        $this->mysqli->close();
    }
}
