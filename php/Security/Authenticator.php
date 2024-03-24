<?php

namespace Security;

use mysqli;

class Authenticator
{
    private $mysqli;

    public function __construct()
    {
        session_start();
        date_default_timezone_set('America/Jamaica');
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

    static public function verifyEmptyTimeslot($ts)
    {
        $mysqli = new mysqli("localhost", "root", "", "138users");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        $selectedDay = $ts->getDay();
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
