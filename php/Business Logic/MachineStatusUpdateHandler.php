<?php

namespace BusinessLogic;

use DataAccess;

// Retreive the session variables set at login
session_start();

class MachineStatusUpdateHandler
{
    private $mysqli;

    public function __construct()
    {
        //access database
        $this->mysqli = new mysqli("localhost", "root", "", "138users");
        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }
    //function that reduces the assignment of all users assigned to the machine thats out of service 
    public function reduceAssignment($failMachine)
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
    public function removeAllUnavailable($failMachine)
    {
        $updateQuery = $this->mysqli->prepare("UPDATE reservations SET user_name = NULL WHERE machine = ?");
        $updateQuery->bind_param("s", $failMachine);
        return $updateQuery->execute();
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
                    //echo "green";
                } else {
                    $this->reduceAssignment($machine);
                    $this->removeAllUnavailable($machine);
                    //echo "red";
                }
            } else {
                echo "fail";
            }
        } else {
            echo "Failed to update machine status.";
        }
    }

    public function closeConnection()
    {
        $this->mysqli->close();
    }
}

if (isset($_POST['machine'])) {
    $machine = $_POST['machine'];
    $machineStatusHandler = new MachineStatusUpdateHandler();
    $machineStatusHandler->updateMachineStatus($machine);

    $machineStatusHandler->closeConnection();
    header("Location: MachineStatusUI.php");
} else {
    echo "Invalid request.";
}
