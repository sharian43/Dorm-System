<?php

namespace Presentation;

use BusinessLogic;
use Users;

require "MachineStatusUI.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Statuses</title>
    <link rel="stylesheet" href="css\laundry.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <script src="js\maint.js"></script>
</head>

<body class="withoutshadow">
    <div id="timeSlotField">
        <div id="topBar">
            <img src="img\Menu Button.png" alt="menu button" id="menu">
            <span id="currTime"></span>
        </div>
        <div id="sidebar">
            <img src="img\closeButton.png" alt="Close Button" id="close">
            <img src="img\profile.svg" alt="profile pic" id="profile">
            <p><?= $_SESSION['firstname'] . " " . $_SESSION['lastname'] ?></p>
            <div class="sideLinks"><a href="http://localhost/Dorm-System/php/Presentation/OverallMaintenanceView.php">Request Overview</a></div>
            <div class="sideLinks selected"><a class="selected" href="http://localhost/Dorm-System/php/Presentation/MachineStatusView.php">Machine Statuses</a></div>
            <div class="sideLinks"><a href="http://localhost/Dorm-System/php/Presentation/login.php">Logout</a></div>
        </div>
        <form class="machineDisplay" action="MachineStatusUI.php" method="post" onsubmit="machineStatusChange(event)">
            <select name="machine" id="machineSelect">
                <Option value="Machine 1">
                    Machine 1
                </Option>
                <option value="Machine 2">
                    Machine 2
                </option>
                <option value="Machine 3">
                    Machine 3
                </option>
                <option value="Machine 4">
                    Machine 4
                </option>
                <option value="Machine 5">
                    Machine 5
                </option>
            </select>
            <?php
            $interact = new MachineStatusUI();
            $reservations = $interact->fetchMachineStatuses();
            for ($machine = 1; $machine <= 5; $machine++) :
                $isAvailable = ($reservations["Machine $machine"] == 1)
            ?>
                <div class="Machine<?= $isAvailable ? " Available" : "" ?>">
                    <img src="<?= $isAvailable ? "img/washing.png" : "img/washingred.png" ?>" alt="Laundry washing" id="machine">
                    <span>Machine <?= $machine; ?></span>
                </div>
            <?php endfor; ?>
            <button type="submit" class="machineButtons">Toggle Availability</button>
        </form>
        <form class="machineDisplay" action="MachineStatusUI.php" method="post" onsubmit="machineStatusChange(event)">
            <select name="machine" id="machineSelect">
                <Option value="Machine 6">
                    Machine 6
                </Option>
                <option value="Machine 7">
                    Machine 7
                </option>
                <option value="Machine 8">
                    Machine 8
                </option>
                <option value="Machine 9">
                    Machine 9
                </option>
                <option value="Machine 10">
                    Machine 10
                </option>
            </select>
            <?php for ($machine = 6; $machine <= 10; $machine++) :
                $isAvailable = ($reservations["Machine $machine"] == 1)
            ?>
                <div class="Machine">
                    <img src="<?= $isAvailable ? "img\washing.png" : "img\washingred.png" ?>" alt="Laundry washing" id="machine">
                    <span>Machine <?= $machine; ?></span>
                </div>
            <?php endfor; ?>
            <button type="submit" class="machineButtons">Toggle Availability</button>
        </form>
    </div>
</body>

</html>