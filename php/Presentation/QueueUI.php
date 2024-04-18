<?php

namespace Presentation;

use Security;
use Users;
use BusinessLogic;


require "../Business Logic/QueueController.php";
require "../Users/Resident.php";

class QueueUI
{
    public function __construct()
    {
    }

    public function generateDailyQueue()
    {
        $controller = new BusinessLogic\QueueContoller();
        return $controller->getDailyTickets();
    }
}
