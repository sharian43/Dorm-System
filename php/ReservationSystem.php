<?php
class ReservationSystem{
   //Initializing variables
    private $mysqli;

    public function __construct() {
        session_start();
        $this->mysqli = new mysqli("localhost", "root", "", "138users");

        if ($this->mysqli->connect_error){
            die("Connection failed: " . $this->mysqli->connect_error);
        }
        //set date function to local time
        date_default_timezone_set('America/New_York');
    }
    
    public function getReservations($selectedDay = null) {
        $query = $this->mysqli->prepare("SELECT machine, timeslot, user_name, day FROM reservations WHERE day = ?");
        //get current day in 0-6 form where sunday is 0.
        $selectedDay = date("w");

        if (isset($_POST['selectedDay'])){
            $selectedDay = $_POST['selectedDay'];
        }
        

        $query->bind_param("s", $selectedDay);

        if ($query->execute()) {
            $result = $query->get_result();
            $reservations = array();
            while ($row = $result->fetch_assoc()) {
                $reservations[$row['machine']][$row['day']][$row['timeslot']] = $row['user_name'];
            }
            return $reservations;
        }
    }
    
    //remove users from timeslots assigned in database
   function removeUnavailable($machine, $timeslot, $day) {
       $updateQuery =  $this->mysqli->prepare("UPDATE reservations SET user_name = NULL WHERE machine = ? AND timeslot = ? and day = ?");
       $updateQuery->bind_param("sss", $machine, $timeslot,$day);
       return $updateQuery->execute();
}

    //get the machine status of all 10 machines and assigns them to whatever variable is calling it.
    public function getMachineStatus($machine) {
        $statusQuery = $this->mysqli->prepare("SELECT machineStatus FROM `machine status` WHERE machineName = ?");
        $statusQuery->bind_param("s", $machine);
        if ($statusQuery->execute()) {
            $statusResult = $statusQuery->get_result();
            $row = $statusResult->fetch_assoc();
            return $row['machineStatus'];
        }
    }

}
    ?>