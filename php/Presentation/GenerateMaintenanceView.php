<?php

namespace Presentation;

use BusinessLogic;
use Users;

require "AuthenticateTicketUI.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waitlist Display</title>
    <link rel="icon" type="image/logo" href="img\laundry logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css\app.css">
    <link rel="stylesheet" href="css\laundry.css">
    <script src="js\side.js"></script>
    <script src="js\maintview.js"></script>
</head>

<body>

    <div id="timeSlotField">
        <div id="topBar">
            <img src="img\Menu Button.png" alt="menu button" id="menu">
            <span id="currTime"></span>
        </div>
        <div id="sidebar">
            <?php $usertype = $_SESSION['role'] ?>
            <?php if ($usertype == "resident") : ?>
                <img src="img\closeButton.png" alt="Close Button" id="close">
                <img src="img\profile.svg" alt="profile pic" id="profile">
                <p><?= $_SESSION['firstname'] . " " . $_SESSION['lastname'] ?></p>
                <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/timeslotView.php">Reservation Schedule</a></div>
                <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/QueueView.php">Waitlist</a></div>
                <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/AuthenticateTicketView.php">Ticket View</a></div>
                <div class="sideLinks selected"><a class="selected" href="http://localhost/dorm-System/php/presentation/GenerateMaintenanceView.php">Maintenance Request</a></div>
                <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/CancelView.php">Cancel Reservation</a></div>
                <div class="sideLinks"><a href="http://localhost/Dorm-System/php/Presentation/login.php">Logout</a></div>
            <?php else : ?>
                <img src="img\closeButton.png" alt="Close Button" id="close">
                <img src="img\profile.svg" alt="profile pic" id="profile">
                <p><?= $_SESSION['firstname'] . " " . $_SESSION['lastname'] ?></p>
                <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/AuthenticateTicketView.php">Ticket Overview</a></div>
                <div class="sideLinks selected"><a class="selected" href="http://localhost/dorm-System/php/presentation/GenerateMaintenanceView.php">Maintenance Request</a></div>
                <div class="sideLinks"><a href="http://localhost/Dorm-System/php/Presentation/login.php">Logout</a></div>
            <?php endif; ?>
        </div>
        <div id="dynamic">
            <div id="maintenanceBody">
                <form method="POST" action="GenerateMaintenenaceUI.php" onsubmit="Request(event)">
                    <div id="requestForm">
                        <p>Describe the issue:</p>
                        <input type="text" id="issue_description" name="issue" rows="4" cols="50" required></textarea><br>
                        <button class="maintButton" type="submit">Submit</button>
                    </div>
                </form>
                <p id="confirmationMessage" style="display: none;">Request was submitted!</p>
            </div>
        </div>
    </div>
</body>

</html>