<?php

namespace Presentation;

use Security;
use users;
use BusinessLogic;

require "../Users/Resident.php";
require "../Business Logic/MaintenanceController.php";
class GenerateMaintenanceUI
{
    public function __construct()
    {
    }
    public function generateRequest($message)
    {
        $controller = new BusinessLogic\MaintenanceController();
        return $controller->generateMaintenanceRequest($message);
    }

    public function fetchRequest()
    {
        $controller = new BusinessLogic\MaintenanceController();
        return $controller->fetchMaintenance();
    }
}


if (isset($_POST["issue"])) {
    $log = new GenerateMaintenanceUI();
    echo $log->generateRequest($_POST["issue"]);
}
