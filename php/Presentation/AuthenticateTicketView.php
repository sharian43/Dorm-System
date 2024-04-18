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
            <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/QueueView.php">Waitlist</a></div>
            <div class="sideLinks selected"><a class="selected" href="http://localhost/dorm-System/php/presentation/AuthenticateTicketView.php">Ticket View</a></div>
            <div class="sideLinks"><a href="MaintenanceRequest.php">Maintenance Request</a></div>
            <div class="sideLinks"><a href="http://localhost/dorm-System/php/presentation/CancelView.php">Cancel Reservation</a></div>
        </div>
        <div id="dynamic">
            <?php
            $usertype = $_SESSION['role'];
            ?>
            <?php if ($usertype == "resident") : ?>
                <?php
                $controller = new BusinessLogic\AuthenticateTicketContoller();
                $authTicks = $controller->authenticateReservation();
                $iter = $authTicks['increment'];
                $user = $_SESSION['username'];
                $three = $authTicks['day'];
                $ticketID = $authTicks['id'];
                $dogs = $authTicks['timeslot'];
                $reservoir = $authTicks['machine'];
                ?>
                <div class="ticketBox">
                    <?php for ($ticket = 0; $ticket < $iter; $ticket++) : ?>
                        <div class="container">
                            <h2>Queue Ticket</h2>
                            <div class="machine">
                                <div id="user">
                                    <h3 id="id">User ID: <?= $user ?></h3>
                                    <h3 id="date">Day: <?= $three[$ticket] ?></h3>
                                </div>
                                <p id="ticket"><strong>Ticket ID:</strong></p>
                                <p id="dec1">********************************************</p>
                                <h3 id="ticket-val">A<?= $ticketID[$ticket] ?></h3>
                                <p id="dec2">********************************************</p>
                                <h4>Description:</h4>
                                <p id="details"><strong>Time Slot:</strong> <?= $dogs[$ticket] ?><br>
                                    <strong>Period:</strong> 60 mins<br>
                                    <strong>Service:</strong> Wash, Rinse & Dry<br>
                                    <strong>Selected Equipment:</strong> <?= $reservoir[$ticket] ?>
                                </p>
                                <p id="message">Tip: Please arrive 5 minutes prior to your scheduled time!</p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php else : ?>
                <div class="ticketBox">
                    <?php
                    $controller = new BusinessLogic\AuthenticateTicketContoller();
                    $authTicks = $controller->authenticateAllReservations();
                    $i = $authTicks['increment'];
                    $users = $authTicks['name'];
                    $day = $authTicks['day'];
                    $userID = $authTicks['id'];
                    $time = $authTicks['timeslot'];
                    $machines = $authTicks['machine'];
                    ?>
                    <?php for ($ticket = 0; $ticket < $i; $ticket++) : ?>
                        <div class="container">
                            <h2>Queue Ticket</h2>
                            <div class="machine">
                                <div id="user">
                                    <h3 id="id">User ID: <?= $users[$ticket] ?></h3>
                                    <h3 id="date">Day: <?= $day[$ticket] ?></h3>
                                </div>
                                <p id="ticket"><strong>Ticket ID:</strong></p>
                                <p id="dec1">********************************************</p>
                                <h3 id="ticket-val">A<?= $userID[$ticket] ?></h3>
                                <p id="dec2">********************************************</p>
                                <h4>Description:</h4>
                                <p id="details"><strong>Time Slot:</strong> <?= $time[$ticket] ?><br>
                                    <strong>Period:</strong> 60 mins<br>
                                    <strong>Service:</strong> Wash, Rinse & Dry<br>
                                    <strong>Selected Equipment:</strong> <?= $machines[$ticket] ?>
                                </p>
                                <p id="message">Tip: Please arrive 5 minutes prior to your scheduled time!</p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
        </div>
    </div>
<?php endif; ?>
</body>

</html>