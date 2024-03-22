<?php
class DBController
{
    //Initializing variables
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli("localhost", "root", "", "138users");

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
        //set date function to local time
        date_default_timezone_set('America/New_York');
    }

    public function getReservations($selectedDay = null)
    {
        $query = $this->mysqli->prepare("SELECT machine, timeslot, user_name, day FROM reservations WHERE day = ?");
        //get current day in 0-6 form where sunday is 0.

        if ($selectedDay==null) {
            $selectedDay = date("w");
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
    function removeUnavailable($machine, $timeslot, $day)
    {
        $updateQuery =  $this->mysqli->prepare("UPDATE reservations SET user_name = NULL WHERE machine = ? AND timeslot = ? and day = ?");
        $updateQuery->bind_param("sss", $machine, $timeslot, $day);
        return $updateQuery->execute();
    }

    //get the machine status of all 10 machines and assigns them to whatever variable is calling it.
    public function getMachineStatus($machine)
    {
        $statusQuery = $this->mysqli->prepare("SELECT machineStatus FROM `machine status` WHERE machineName = ?");
        $statusQuery->bind_param("s", $machine);
        if ($statusQuery->execute()) {
            $statusResult = $statusQuery->get_result();
            $row = $statusResult->fetch_assoc();
            return $row['machineStatus'];
        }
    }

    function getTyped(mysqli $mysqli, $username)
    {
        $query = $mysqli->prepare("SELECT firstname, lastname, usertype FROM dorm WHERE username = ?");
        if ($query) {
            $query->bind_param("s", $username);
            if ($query->execute()) {
                $query->store_result();
                if ($query->num_rows === 1) {
                    $query->bind_result($firstname, $lastname, $usertype);
                    $query->fetch();
                    $typeInformation = ['first' => $firstname, 'last' => $lastname, 'type' => $usertype];
                    $query->close();
                    return $typeInformation;
                }
            }
        }
    }

    public function assignUserTimeslot($user, $ts)
    {
        $reservations = array();
        $selectedDay = $ts->getDay();
        if ($selectedDay == "null") {
            $selectedDay = Date("w");
        }
        var_dump($selectedDay . "selected ");

        //assign data that was posted to the handler to variables
        $timeslot12 = $ts->getTime();
        $machinery = $ts->getMachineNum();
        $timestamp = strtotime($timeslot12);
        $timeslot24 = date('H:i:s', $timestamp);
        $username = "1234";
        if ($this->checkLimit($user->getId())) {
            $updateQuery = $this->mysqli->prepare("UPDATE reservations SET user_name = ? WHERE machine = ? AND timeslot = ? AND day = ?");
            $updateQuery->bind_param("ssss", $username, $machinery, $timeslot24, $selectedDay);
            if ($updateQuery->execute()) {
                $updateQuery->close();
                return "success";
            } else {
                $updateQuery->close();
                return "failure";
            }

            
        } else {
            return "limited";
        }
    }


    //queries the database for the number of timeslots the user has assigned itself to for the week
    private function checkLimit($user)
    {
        $limitQuery = $this->mysqli->prepare("SELECT assignments FROM dorm WHERE username=?");

        if ($limitQuery->bind_param("s", $user)) {
            if ($limitQuery->execute()) {
                $results = $limitQuery->get_result();
                $rows = $results->fetch_assoc();

                if ($rows["assignments"] < 2) {
                    $updateLimitQuery = $this->mysqli->prepare("UPDATE dorm SET assignments = assignments + 1 WHERE username=?");

                    if ($updateLimitQuery->bind_param("s", $user)) {
                        if ($updateLimitQuery->execute()) {
                            $limitQuery->close();
                            $updateLimitQuery->close();
                            return true;
                        }
                    }
                } else {
                    return false;
                }
            }
        }
    }

    public function closeConnection()
    {
        $this->mysqli->close();
    }
}
