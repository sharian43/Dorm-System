<?php

class Authenticator
{
    private $mysqli;

    public function __construct()
    {
        session_start();
        date_default_timezone_set('America/New_York');
    }

    public function login($username, $password)
    {
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

    public function verifyEmptyTimeslot($ts)
    {
        $this->mysqli = new mysqli("localhost", "root", "", "138users");

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
        $selectedDay = $ts->getDay();
        $timeslot12 = $ts->getTime();
        $machinery = $ts->getMachineNum();
        $timestamp = strtotime($timeslot12);
        $timeslot24 = date('H:i:s', $timestamp);

        $query = $this->mysqli->prepare("SELECT user_name FROM reservations WHERE timeslot = ? AND machine = ? AND day = ?");
        $query->bind_param("sss", $timeslot24, $machinery, $selectedDay);

        if ($query->execute()) {
            $result = $query->get_result();
            $row = $result->fetch_assoc();
            $query->close();

            if ($row != null && $row["user_name"] == null) {
                $this->closeConnection();
                return True;
            }
        }
        $this->closeConnection();
        return False;
    }

    public function closeConnection()
    {
        $this->mysqli->close();
    }
}
