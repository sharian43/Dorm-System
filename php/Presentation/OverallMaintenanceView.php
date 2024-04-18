<?php

namespace Presentation;

use BusinessLogic;
use Users;

require "GenerateMaintenanceUI.php";
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancellation</title>
    <link rel="stylesheet" href="css\cancel.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css\laundry.css" />
    <script src="js\side.js"></script>
</head>

<body>
    <div id="timeSlotField">
        <div id="topBar">
            <img src="img\Menu Button.png" alt="menu button" id="menu">
            <span id="currTime"></span>
        </div>
        <div id="sidebar">
            <img src="img\closeButton.png" alt="Close Button" id="close">
            <img src="img\profile.svg" alt="profile pic" id="profile">
            <p><?= $_SESSION['firstname'] . " " . $_SESSION['lastname'] ?></p>
            <div class="sideLinks selected"><a class="selected" href="http://localhost/Dorm-System/php/Presentation/OverallMaintenanceView.php">Request Overview</a></div>
            <div class="sideLinks"><a href="http://localhost/Dorm-System/php/Presentation/MachineStatusView.php">Machine Statuses</a></div>
            <div class="sideLinks"><a href="http://localhost/Dorm-System/php/Presentation/login.php">Logout</a></div>
        </div>
        <div id="dynamic">
            <div id="cancellationForm">
                <!--Display the available cancellation timeslots using iter instead of number variable due to assignments not being renewed till next week for expired timeslots-->
                <?php
                $interactions = new GenerateMaintenanceUI();
                $inform = $interactions->fetchRequest();
                ?>
                <?php foreach ($inform['dates'] as $index => $date) : ?>
                    <p class="check"><?= $date . " - " . $inform['issues'][$index] ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>