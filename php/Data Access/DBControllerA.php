<?php

class DBControllerA
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli("localhost", "root", "", "138users");
        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    public function getUserType($username)
    {
        $typeQuery = $this->mysqli->prepare("SELECT usertype FROM dorm WHERE username=?");
        $typeQuery->bind_param("s", $username);

        if ($typeQuery->execute()) {
            $typeResult = $typeQuery->get_result();
            $typeRow = $typeResult->fetch_assoc();
            return $typeRow['usertype'];
        }
        return null;
    }

    public function getUserAssignments($username)
    {
        $assignmentQuery = $this->mysqli->prepare("SELECT assignments FROM dorm WHERE username=?");
        $assignmentQuery->bind_param("s", $username);

        if ($assignmentQuery->execute()) {
            $result = $assignmentQuery->get_result();
            $row = $result->fetch_assoc();
            return $row["assignments"];
        }
        return null;
    }

    public function getUserReservations($username)
    {
        $machineQuery = $this->mysqli->prepare("SELECT id, machine, timeslot, day FROM reservations WHERE user_name = ?");
        $machineQuery->bind_param("s", $username);

        if ($machineQuery->execute()) {
            $result = $machineQuery->get_result();
            $reservations = [];
            while ($rows =  $result->fetch_assoc()) {
                $reservation = [
                    'id' => $rows["id"],
                    'machine' => $rows["machine"],
                    'timeslot' => $rows["timeslot"],
                    'day' => $rows["day"],
                ];
                $reservations[] = $reservation;
            }
            return $reservations;
        }
        return null;
    }

    public function getAllReservations()
    {
        $adminQuery = $this->mysqli->prepare("SELECT id, machine, timeslot, day, user_name FROM reservations WHERE user_name is NOT NULL");

        if ($adminQuery->execute()) {
            $resultant = $adminQuery->get_result();
            $reservations = [];
            while ($rowing =  $resultant->fetch_assoc()) {
                $reservation = [
                    'id' => $rowing["id"],
                    'machine' => $rowing["machine"],
                    'timeslot' => $rowing["timeslot"],
                    'day' => $rowing["day"],
                    'user_name' => $rowing["user_name"],
                ];
                $reservations[] = $reservation;
            }
            return $reservations;
        }
        return null;
    }
}
