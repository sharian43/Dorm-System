<?php

namespace BusinessLogic;

use DataAccess;

require "../Data Access/DBController.php";
require "Request.php";
session_start();

class MaintenanceController
{
    private $mysqli;

    public function __construct()
    {
    }

    public function generateMaintenanceRequest($message)
    {
        $controller = new DataAccess\DBController;
        $request = new Request($message, $_SESSION['username']);
        $message = $controller->submitRequest($request);
        $controller->closeConnection();
        return $message;
    }

    public function fetchMaintenance()
    {
        $controller = new DataAccess\DBController;
        $messages = $controller->getMaintenanceRequest();
        return $messages;
    }


    public function closeConnection()
    {
        $this->mysqli->close();
    }
}
