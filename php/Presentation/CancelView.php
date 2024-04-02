<?php

namespace Presentation;

use BusinessLogic;
use Users;

require "CancelUI.php";
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
    <script src="js\cancel.js"></script>
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
            <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/timeslotView.php">Reservation Schedule</a></div>
            <div class="sideLinks"><a href="WaitlistDisplay.php">Waitlist</a></div>
            <div class="sideLinks"><a href="TicketGenerator.php">Ticket View</a></div>
            <div class="sideLinks"><a href="MaintenanceRequest.php">Maintenance Request</a></div>
            <div class="sideLinks selected"><a class="selected" href="http://localhost/dorm-System/php/presentation/CancelView.php">Cancel Reservation</a></div>
        </div>
        <div id="dynamic">
            <div id="cancellationForm">
                <!--Display the available cancellation timeslots using iter instead of number variable due to assignments not being renewed till next week for expired timeslots-->
                <?php
                $weekDays = [0 => "Sunday", 1 => "Monday", 2 => "Tuesday", 3 => "Wednesday", 4 => "Thursday", 5 => "Friday", 6 => "Saturday"];
                $interaction = new CancelUI();
                $info = $interaction->cancelReservationSlots();
                ?>
                <?php for ($picket = 0; $picket < $info['iter']; $picket++) : ?>
                    <div onclick="canceller(event)" class="timeSlotted selected"><?= $info['clock'][$picket] . " " . $info['mech'][$picket] . " " . $weekDays[$info['week'][$picket]] ?></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>

</html>