<?php

class AuthenticateTicketUI
{
    public function authenticateTicketUI()
    {
        session_start();

        require_once 'DBControllerA.php';
        require_once 'AuthenticateController.php';

        $dbController = new DBControllerA();
        $username = $_SESSION['userName'];
        $usertype = $dbController->getUserType($username);

        if ($usertype == "resident") {
            $assignments = $dbController->getUserAssignments($username);
            $reservations = $dbController->getUserReservations($username);
        } else {
            $reservations = $dbController->getAllReservations();
        }

        AuthenticateController::generateHTML($reservations, $username);
    }
}


$authTicketUI = new AuthenticateTicketUI();
$authTicketUI->authenticateTicketUI();

?>
