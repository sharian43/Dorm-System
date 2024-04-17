<?php

class QueueUI {
    public function queueUI() {
    {
        session_start();

        require_once 'DBControllerA.php';
        require_once 'QueueController.php';

        $dbController = new DBControllerA();
        $username = $_SESSION['userName'];
        $usertype = $dbController->getUserType($username);

        if ($usertype == "resident") {
            $assignments = $dbController->getUserAssignments($username);
            $reservations = $dbController->getUserReservations($username);
        } else {
            $reservations = $dbController->getAllReservations();
        }

        QueueController::generateHTML($reservations, $username);
    }
    }
    
}

$queueUi = new QueueUI()
$queueUi -> queueUI()

?>