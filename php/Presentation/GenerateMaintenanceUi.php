<?php

namespace Presentation;

use BusinessLogic;
use Users;

require "../Business Logic/TimeslotController.php";
require "../Users/Resident.php";

class GenerateMaintenanceUI {
    private $issueDescription;

    public function __construct($issueDescription ="") {
        $this->issueDescription = $issueDescription;
    }

    public function setIssueDescription($issueDescription) {
        $this->issueDescription = $issueDescription;
    }

    public function getIssueDescription() {
        return $this->issueDescription;
    }

    public function generateRequest() {
        // Instantiate a Request object with the current time
        return new Request($this->issueDescription, new DateTime());
    }
}

class Request {
    private $request;
    private $time;

    public function __construct($request, $time) {
        $this->request = $request;
        $this->time = $time;
    }

    public function getRequest() {
        return $this->request;
    }

    public function getTime() {
        return $this->time;
    }
}
?>
