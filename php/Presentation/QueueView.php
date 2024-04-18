<?php

namespace Presentation;

use BusinessLogic;
use Users;

require "QueueUI.php";
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
    <link rel="stylesheet" href="css\styles.css">
    <link rel="stylesheet" href="css\laundry.css">
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
            <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/timeslotView.php">Reservation Schedule</a></div>
            <div class="sideLinks selected"><a class="selected" href="http://localhost/dorm-System/php/presentation/QueueView.php">Waitlist</a></div>
            <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/AuthenticateTicketView.php">Ticket View</a></div>
            <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/GenerateMaintenanceView.php">Maintenance Request</a></div>
            <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/CancelView.php">Cancel Reservation</a></div>
            <div class="sideLinks"><a href="http://localhost/Dorm-System/php/Presentation/login.php">Logout</a></div>
        </div>
        <div id="dynamic">
            <div id="waitlist-container">
                <h2>Queue Display</h2>
                <h3>NOW SERVING</h3>
                <table id="nowserving-table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Name</th>
                            <th>Machine </th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="serving">
                        <?php
                        $ticketInteraction = new QueueUI();
                        $ticketList = $ticketInteraction->generateDailyQueue();
                        ?>
                        <?php foreach ($ticketList['nowServing'] as $personWaiting) : ?>
                            <tr>
                                <td><?= $personWaiting['ticketNumber'] ?></td>
                                <td><?= $personWaiting['name'] ?></td>
                                <td><?= $personWaiting['machine'] ?></td>
                                <td><?= $personWaiting['status'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h3>IN - WAITING</h3>
                <table id="waitlist-table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Name</th>
                            <th>Machine</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="Waitlist">
                        <?php foreach ($ticketList['waitlist'] as $personWaiting) : ?>
                            <tr>
                                <td><?= $personWaiting['ticketNumber'] ?></td>
                                <td><?= $personWaiting['name'] ?></td>
                                <td><?= $personWaiting['machine'] ?></td>
                                <td><?= $personWaiting['status'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>