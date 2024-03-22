<?php
require 'DBController.php';
require "Resident.php";
require "Timeslot.php";

class TimeslotController
{
    function __construct()
    {
    }
    public function fetchStatus($machineKey)
    {
        $controller = new DBController();
        $message = $controller->getMachineStatus($machineKey);
        $controller->closeConnection();
        return $message;
    }

    public function timeslotRemover($machineKey, $timeslot, $selectedDay)
    {
        $controller = new DBController();
        $controller->removeUnavailable($machineKey, $timeslot, $selectedDay);
        $controller->closeConnection();
        return;
    }

    public function assignTimeslot($machineKey, $timeslot, $selectedDay)
    {
        $db = new DBController();
        $username = $_SESSION['userName'];
        $firstname = $_SESSION['fname'];
        $lastname = $_SESSION['lname'];
        $user = new Resident($username, $firstname, $lastname);
        $ts = new Timeslot($selectedDay, $machineKey, $timeslot);
        $message = $db->assignUserTimeslot($user, $ts);
        $db->closeConnection();
        return $message;
    }

    public function checkingReservations($selectedDay){
        $db = new DBController();
        $message = $db->getReservations($selectedDay);
        $db->closeConnection();
        return $message;
    }

    public function dailyReservations($selectedDay)
    {
        $db = new DBController();
        $message = $db->getReservations($selectedDay);
        $htmlcontentt = "";
        for ($machine = 1; $machine <= 10; $machine++) {
            $htmlcontentt .= "<div class=\"machine\">
                <img src=\"" . ($db->getMachineStatus("Machine $machine") == 1 ? "img\washing.png" : "img\washingred.png") . "\" alt=\"Laundry washing\" id=\"machine\">
                <span>Machine $machine</span>
            </div>";

            for ($hour = 8; $hour <= 20; $hour++) {
                $timeslot = sprintf('%02d:00:00', $hour);
                $machineKey = "Machine $machine";
                $isSelected = isset($message[$machineKey][$selectedDay][$timeslot]);
                $currentTime = date('H:00:00');
                $isUnavailable = (($timeslot < $currentTime && $selectedDay == date("w")) || ($hour == date('H') && $selectedDay == date("w")) || $selectedDay < date("w") || ($db->getMachineStatus($machineKey) == 0));

                $htmlcontentt .= "<div class=\"timeSlot" . ($isSelected ? ' selected' : '') . ($isUnavailable ? ' unavailable' : '') . "\">"
                    . ($hour % 12 ?: 12) . ":00 " . ($hour < 12 ? 'AM' : 'PM') . "</div>";

                if ($isUnavailable && $isSelected && $hour != date('H')) {
                    $db->removeUnavailable($machineKey, $timeslot, $selectedDay);
                }
            }
        }

        $db->closeConnection();
        return $htmlcontentt;
    }
}
