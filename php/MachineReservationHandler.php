<?php
session_start();

class MachineReservationHandler
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli("localhost", "root", "", "138users");

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    public function handleReservation()
    {
        $reservations = array();
        $selectedDay = date("w");

        //check if data was posted
        if (isset($_POST['selectedDay'])) {
            $selectedDay = $_POST['selectedDay'];
        }
        //assign data that was posted to the handler to variables
        if (isset($_POST['timeslot'])) {
            $timeslot12 = $_POST['timeslot'];
            $machinery = $_POST['machine'];
            $timestamp = strtotime($timeslot12);
            $timeslot24 = date('H:i:s', $timestamp);
            $username = $_SESSION['userName'];
            if ($this->checkLimit($username)) {
                $updateQuery = $this->mysqli->prepare("UPDATE reservations SET user_name = ? WHERE machine = ? AND timeslot = ? AND day = ?");
                $updateQuery->bind_param("ssss", $username, $machinery, $timeslot24, $selectedDay);

                if ($updateQuery->execute()) {
                    echo "success";
                } else {
                    echo "failure";
                }

                $updateQuery->close();
            } else {
                echo "limited";
            }
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

$handler = new MachineReservationHandler();
$handler->handleReservation();
$handler->closeConnection();
