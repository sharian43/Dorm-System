<?php

class MachineStatusUpdate {
    private $mysqli;
    private $reservations;

    public function __construct() {
        session_start();
        $this->mysqli = new mysqli("localhost", "root", "", "138users");

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }

        $this->fetchMachineStatuses();
    }

    private function fetchMachineStatuses() {
        $query = $this->mysqli->prepare("SELECT machineName, machineStatus FROM `machine status`");
        if ($query->execute()) {
            $result = $query->get_result();
            $this->reservations = [];
            while ($row = $result->fetch_assoc()) {
                $this->reservations[$row['machineName']] = $row['machineStatus'];
            }
        }
    }

    public function generateMachineStatus() {
        $html = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Machine Statuses</title>
                    <link rel="stylesheet" href="css/laundry.css">
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
                    <script src="js/status.js"></script>
                </head>
                <body>
                    <div class="machineDisplayer">';

        // Display form for machines 1-5
        $html .= '<form class="machineDisplay" action="MachineStatusUpdateHandler.php" method="post" onsubmit="machineStatusChange(event)">
                    <select name="machine" id="machineSelect">
                        <option value="Machine 1">Machine 1</option>
                        <option value="Machine 2">Machine 2</option>
                        <option value="Machine 3">Machine 3</option>
                        <option value="Machine 4">Machine 4</option>
                        <option value="Machine 5">Machine 5</option>
                    </select>';
        
        for ($machine = 1; $machine <= 5; $machine++) {
            $isAvailable = ($this->reservations["Machine $machine"] == 1);
            $html .= '<div class="Machine' . ($isAvailable ? " Available" : "") . '">
                        <img src="img/' . ($isAvailable ? "washing.png" : "washingred.png") . '" alt="Laundry washing" id="machine">
                        <span>Machine ' . $machine . '</span>
                    </div>';
        }

        $html .= '<button type="submit" class="machineButtons">Toggle Availability</button>
                </form>';

        // Display form for machines 6-10
        $html .= '<form class="machineDisplay" action="MachineStatusUpdateHandler.php" method="post" onsubmit="machineStatusChange(event)">
                    <select name="machine" id="machineSelect">
                        <option value="Machine 6">Machine 6</option>
                        <option value="Machine 7">Machine 7</option>
                        <option value="Machine 8">Machine 8</option>
                        <option value="Machine 9">Machine 9</option>
                        <option value="Machine 10">Machine 10</option>
                    </select>';

        for ($machine = 6; $machine <= 10; $machine++) {
            $isAvailable = ($this->reservations["Machine $machine"] == 1);
            $html .= '<div class="Machine">
                        <img src="img/' . ($isAvailable ? "washing.png" : "washingred.png") . '" alt="Laundry washing" id="machine">
                        <span>Machine ' . $machine . '</span>
                    </div>';
        }

        $html .= '<button type="submit"  class="machineButtons">Toggle Availability</button>
                </form>
            </div>
            <script src="js/status.js"></script>
        </body>
        </html>';
        return  $html;
    
    }
}

$machineStatusUpdate = new MachineStatusUpdate();
echo $machineStatusUpdate->generateMachineStatus();

?>