<?php

namespace Security;

use mysqli;

class Authenticator
{
    private $mysqli;

    public function __construct()
    {
        date_default_timezone_set('America/Jamaica');
        $this->mysqli = new mysqli("localhost", "root", "", "138users");

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    public function loginUser($username, $password)
    {
        $username = $this->mysqli->real_escape_string($username);
        $query = $this->mysqli->prepare("SELECT password, usertype, firstname, lastname FROM dorm WHERE username = ?");
        if ($query) {
            $query->bind_param("s", $username);
            if ($query->execute()) {
                $query->store_result();
                if ($query->num_rows === 1) {
                    $query->bind_result($storedPassword, $role, $firstname, $lastname);
                    $query->fetch();
                    $query->close();
                    $info = ['password' => $storedPassword, 'type' => $role, 'firstname' => $firstname, 'lastname' => $lastname];
                    return $info;
                } else {
                    $info = ['password' => "incorrect", 'type' => "incorrect"];
                    return $info;
                }
            }
        }
        return "incorrect";
    }

    public function logout()
    {
    }

    public function checkLogin()
    {
        if (isset($_SESSION['userName'])) {
            return true;
        } else {
            return false;
        }
    }

    public function authenticateResident()
    {
        if ($_SESSION['role'] == 'resident') {
            return true;
        } else {
            return false;
        }
    }

    public function authenticateLaundryStaff()
    {
        if ($_SESSION['role'] == "laundry") {
            return true;
        } else {
            return false;
        }
    }

    public function authenticateMaintStaff()
    {
        if ($_SESSION["role"] == "maintenance") {
            return true;
        }
        return false;
    }


    static public function verifyEmptyTimeslot($ts)
    {
        $mysqli = new mysqli("localhost", "root", "", "138users");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        $days = ["Sunday" => 0, "Monday" => 1, "Tuesday" => 2, "Wednesday" => 3, "Thursday" => 4, "Friday" => 5, "Saturday" => 6];
        $selectedDay = $days[$ts->getDay()];
        $timeslot12 = $ts->getTime();
        $machinery = $ts->getMachineNum();
        $timestamp = strtotime($timeslot12);
        $timeslot24 = date('H:i:s', $timestamp);
        $query = $mysqli->prepare("SELECT user_name FROM reservations WHERE timeslot = ? AND machine = ? AND day = ?");
        $query->bind_param("sss", $timeslot24, $machinery, $selectedDay);

        if ($query->execute()) {
            $result = $query->get_result();
            $row = $result->fetch_assoc();
            $query->close();

            if ($row != null && $row["user_name"] == null) {
                self::closeConnection($mysqli);
                return True;
            }
        }
        self::closeConnection($mysqli);
        return False;
    }

    static public function closeConnection($mysqli)
    {
        $mysqli->close();
    }
}
