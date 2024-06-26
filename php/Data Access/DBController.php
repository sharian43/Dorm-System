<?php

namespace DataAccess;


use mysqli;
use Security;
use Security\Authenticator;

require "../Security/Authenticator.php";

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
        date_default_timezone_set('America/Jamaica');
    }

    public function getReservations($selectedDay = null)
    {
        $query = $this->mysqli->prepare("SELECT machine, timeslot, user_name, day FROM reservations WHERE day = ?");
        //get current day in 0-6 form where sunday is 0.

        if ($selectedDay == null) {
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

    public function fetchStats()
    {
        $query = $this->mysqli->prepare("SELECT machineName, machineStatus FROM `machine status`");
        if ($query->execute()) {
            $result = $query->get_result();
            $reservations = [];
            while ($row = $result->fetch_assoc()) {
                $reservations[$row['machineName']] = $row['machineStatus'];
            }
            return $reservations;
        }
    }
    public function getTyped(mysqli $mysqli, $username)
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
        $days = ["Sunday" => 0, "Monday" => 1, "Tuesday" => 2, "Wednesday" => 3, "Thursday" => 4, "Friday" => 5, "Saturday" => 6];
        $reservations = array();
        $selectedDay = $days[$ts->getDay()];
        if ($selectedDay == "null") {
            $selectedDay = Date("w");
        }
        //assign data that was posted to the handler to variables
        $timeslot12 = $ts->getTime();
        $machinery = $ts->getMachineNum();
        $timestamp = strtotime($timeslot12);
        $timeslot24 = date('H:i:s', $timestamp);
        $username = $user->getId();
        if (Authenticator::verifyEmptyTimeslot($ts)) {
            if ($this->checkLimit($user->getId())) {
                $updateQuery = $this->mysqli->prepare("UPDATE reservations SET user_name = ? WHERE machine = ? AND timeslot = ? AND day = ?");
                $updateQuery->bind_param("ssss", $username, $machinery, $timeslot24, $selectedDay);
                if ($updateQuery->execute()) {
                    return "success";
                } else {
                    return "failure";
                }
            } else {
                return "limited";
            }
        } else {
            return "unavailable";
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

    private function reduceAssignment($failMachine)
    {
        $usersQuery = $this->mysqli->prepare("SELECT username FROM dorm WHERE assignments > 0 AND username IN (SELECT user_name FROM reservations WHERE machine = ? AND user_name IS NOT NULL)");
        $usersQuery->bind_param("s", $failMachine);

        if ($usersQuery->execute()) {
            $result = $usersQuery->get_result();
            // iterate through users and reduce their assignments by 1 for every user assigned to the specified machine
            while ($row = $result->fetch_assoc()) {
                $user = $row['username'];
                $negativeQuery = $this->mysqli->prepare("UPDATE dorm SET assignments = assignments - 1 WHERE username = ?");
                $negativeQuery->bind_param("s", $user);
                $negativeQuery->execute();
            }
        }
    }

    //remove All users from the machine in maintenance
    private function removeAllUnavailable($failMachine)
    {
        $updateQuery = $this->mysqli->prepare("UPDATE reservations SET user_name = NULL WHERE machine = ?");
        $updateQuery->bind_param("s", $failMachine);
        return $updateQuery->execute();
    }

    public function fetchResidentTickets()
    {
        $user = $_SESSION['username'];
        $machineQuery = $this->mysqli->prepare("SELECT id, machine, timeslot, day FROM reservations WHERE user_name = ?");
        $machineQuery->bind_param("s", $user);

        if ($machineQuery->execute()) {
            $result = $machineQuery->get_result();
            $reservoir = array();
            $dogs = array();
            $three = array();
            $ticketID = array();
            $iter = 0;
            while ($rows =  $result->fetch_assoc()) {
                $reservoir[$iter] = $rows["machine"];
                $dogs[$iter] = $rows['timeslot'];
                $three[$iter] = $rows['day'];
                $ticketID[$iter] = $rows['id'];
                $iter++;
            }
            $tickets = array(
                "machine" => $reservoir,
                "timeslot" => $dogs,
                "day" => $three,
                "id" => $ticketID,
                "increment" => $iter,
            );
            return $tickets;
        }
    }

    public function fetchLaundryTickets()
    {
        $adminQuery = $this->mysqli->prepare("SELECT id, machine, timeslot, day, user_name FROM reservations WHERE user_name is NOT NULL");


        if ($adminQuery->execute()) {
            $resultant = $adminQuery->get_result();
            $machines = array();
            $time = array();
            $day = array();
            $userID = array();
            $i = 0;
            while ($rowing =  $resultant->fetch_assoc()) {
                $machines[$i] = $rowing["machine"];
                $time[$i] = $rowing['timeslot'];
                $day[$i] = $rowing['day'];
                $userID[$i] = $rowing['id'];
                $users[$i] = $rowing['user_name'];
                $i++;
            }
            $tickets = array(
                "machine" => $machines,
                "timeslot" => $time,
                "day" => $day,
                "id" => $userID,
                "name" => $users,
                "increment" => $i,
            );
            return $tickets;
        }
    }


    public function getCancelSlots()
    {
        $user = $_SESSION['username'];
        //get the number of timeslots the user is assigned to
        $assignmentQuery = $this->mysqli->prepare("SELECT assignments FROM dorm WHERE username=?");
        $assignmentQuery->bind_param("s", $user);

        //assign the number of timeslot user is assigned to to number variable
        if ($assignmentQuery->execute()) {
            $result = $assignmentQuery->get_result();
            $row = $result->fetch_assoc();
            $number = $row["assignments"];
        }

        //get the information of the specified user timeslots that are still available to be cancelled
        $infoQuery = $this->mysqli->prepare("SELECT machine, timeslot, day FROM reservations WHERE user_name = ?");
        $infoQuery->bind_param("s", $user);

        //assign these timeslots to array variables
        if ($infoQuery->execute()) {
            $result = $infoQuery->get_result();
            $mech = array();
            $clock = array();
            $week = array();
            $iter = 0;
            while ($rows =  $result->fetch_assoc()) {
                $mech[$iter] = $rows["machine"];
                $clock[$iter] = $rows['timeslot'];
                $week[$iter] = $rows['day'];
                $iter++;
            }
            return array('mech' => $mech, 'week' => $week, 'clock' => $clock, 'iter' => $iter);
        }
    }

    public function removeTimeslot($machine, $timeslot, $selectedDay, $user)
    {
        $uname = $user->getId();
        $days = ["Sunday" => 0, "Monday" => 1, "Tuesday" => 2, "Wednesday" => 3, "Thursday" => 4, "Friday" => 5, "Saturday" => 6];
        //get the total number of reservations the user has made for the week(assignments) 
        $assignmentQuery = $this->mysqli->prepare("SELECT assignments FROM dorm WHERE username=?");
        $assignmentQuery->bind_param("s", $uname);

        if ($assignmentQuery->execute()) {
            $result = $assignmentQuery->get_result();
            $row = $result->fetch_assoc();
            $number = $row["assignments"];
        }

        //Remove the user from the assoicated timeslot
        $updateQuery = $this->mysqli->prepare("UPDATE reservations SET user_name=null WHERE machine=? AND day= ? AND timeslot=?");
        $updateQuery->bind_param("sss", $machine, $days[$selectedDay], $timeslot);

        if ($updateQuery->execute()) {
            if ($number > 0) {
                //Reduce the users total number of reservations made for the week allowing them to reassign to another timeslot for the week.
                $this->mysqli->query("UPDATE dorm SET assignments = assignments - 1 WHERE username = '$uname'");
                return "success";
            }
        } else {
            return "failure";
        }
    }

    //get the machine status of the selected machine
    public function updateMachineStatus($machine)
    {
        $statusQuery = $this->mysqli->prepare("SELECT machineStatus FROM `machine status` WHERE machineName=?");
        $statusQuery->bind_param("s", $machine);

        if ($statusQuery->execute()) {
            $result = $statusQuery->get_result();
            $row = $result->fetch_assoc();
            $status = $row["machineStatus"];

            if ($status == 0) {
                $updateStatus = 1;
            } else {
                $updateStatus = 0;
            }

            $updateQuery = $this->mysqli->prepare("UPDATE `machine status` SET machineStatus = ? WHERE machineName = ?");
            $updateQuery->bind_param("ss", $updateStatus, $machine);

            if ($updateQuery->execute()) {
                if ($status == 0) {
                    return "green";
                } else {
                    $this->reduceAssignment($machine);
                    $this->removeAllUnavailable($machine);
                    return "red";
                }
            } else {
                return "fail";
            }
        } else {
            return "Failed to update machine status.";
        }
    }

    private function currentUsers()
    {
        $user = $_SESSION['username'];
        $currentDay = date('w');


        //Get current users selected machines and timeslot (if any) for current day from database
        $query = $this->mysqli->prepare("SELECT id, machine, timeslot, user_name FROM reservations WHERE user_name = ? AND day = ?");
        $query->bind_param("ss", $user, $currentDay);

        if ($query->execute()) {
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            }
            return null;
        }
    }

    private function allUserNames()
    {
        $nameQuery = $this->mysqli->query("SELECT firstname, lastname, username FROM dorm");

        if ($nameQuery) {
            $usernames = array();

            while ($rower = $nameQuery->fetch_assoc()) {
                $username = $rower['username'];
                $firstname = $rower['firstname'];
                $lastname = $rower['lastname'];
                $usernames[$username] = array('firstname' => $firstname, 'lastname' => $lastname);
            }
            return $usernames;
        }
    }

    public function ticketDisplay()
    {
        $row = $this->currentUsers();
        $usernames = $this->allUserNames();
        $currentHour = date('H');
        $currentDay = date('w');
        $machineQuery = $this->mysqli->prepare("SELECT id, machine, HOUR(timeslot) as hour, day, user_name FROM reservations WHERE machine = ? AND day = ? AND user_name IS NOT NULL");
        $nowServing = array();
        $waitlist = array();
        $machineQuery->bind_param("ss", $row["machine"], $currentDay);

        if ($machineQuery->execute()) {
            $result = $machineQuery->get_result();
            $waitlist = array();
            $nowServing = array();
            while ($rows = $result->fetch_assoc()) {
                $status = (intval($rows['hour']) === intval($currentHour)) ? "Now Serving" : "In Waiting";
                $personWaiting = array(
                    'ticketNumber' => "A" . $rows['id'],
                    'name' => $usernames[$rows['user_name']]['firstname'] . " " . $usernames[$rows['user_name']]['lastname'],
                    'machine' => $rows['machine'],
                    'status' => $status,
                );

                if ($status === "Now Serving") {
                    $nowServing[] = $personWaiting;
                } else {
                    $waitlist[] = $personWaiting;
                }
            }
            $ticketList = array(
                "nowServing" => $nowServing,
                "waitlist" => $waitlist,
            );
            return $ticketList;
        }
    }

    public function submitRequest($request)
    {
        $issue = $request->getIssue();
        $issueQuery = $this->mysqli->prepare("INSERT INTO requests (issue) VALUES (?)");
        $issueQuery->bind_param("s", $issue);
        if ($issueQuery->execute()) {
            return "stored";
        } else {
            return "incorrect";
        }
    }

    public function getMaintenanceRequest()
    {
        $issues = [];
        $dates = [];

        $issueQuery = $this->mysqli->prepare("SELECT issue, submission_time FROM requests");
        if ($issueQuery) {
            if ($issueQuery->execute()) {
                $result = $issueQuery->get_result();
                if ($result->num_rows > 0) {
                    while ($rows = $result->fetch_assoc()) {
                        $issues[] = $rows["issue"];
                        $dates[] = $rows["submission_time"];
                    }
                    $maintIssue = array(
                        "issues" => $issues,
                        "dates" => $dates,
                    );
                    $issueQuery->close();
                    return $maintIssue;
                } else {
                    return ["issues" => [], "dates" => []];
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }



    public function closeConnection()
    {
        $this->mysqli->close();
    }
}
