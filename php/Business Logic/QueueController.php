<?php

class QueueController
{
    private static function calculateUserPosition($reservations, $username) {
        // Sort reservations based on the scheduled timeslot
        usort($reservations, function($a, $b) {
            return strtotime($a['timeslot']) - strtotime($b['timeslot']);
        });

        // Find user's reservation
        foreach ($reservations as $key => $reservation) {
            if ($reservation['user_name'] == $username) {
                // Add 1 to display the position starting from 1 instead of 0
                return $key + 1;
            }
        }

        // If user's reservation not found, return 0
        return 0;
    }
    
    public static function generateHTML($reservations, $username)
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ticket View</title>
            <link rel="icon" type="image/logo" href="img\laundry logo.png">
            <link rel="stylesheet" href="css\app.css">
        </head>
        <body>
            <h1>LAUNDRY LINK</h1>
            <div></div>
            <?php 
            // Calculate user's position in line outside the loop
            $userPosition = self::calculateUserPosition($reservations, $username);
            ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="container">
                    <h2>Queue Display</h2>
                    <h3>NOW SERVING</h3>
                    <div id="user">
                        <h3 id="id">User ID: <?= $username ? $username : 'resident' ?></h3>
                        <h3 id="date">Day: <?= $reservation['day'] ?></h3>
                    </div>
                    <p id="ticket"><strong>Ticket ID:</strong></p>
                    <p id="dec1">********************************************</p>
                    <h3 id="ticket-val">A<?= $reservation['id'] ?></h3>
                    <p id="dec2">********************************************</p>
                    <!-- Display user's position in line -->
                    <p>Your position in line: <?= $userPosition ?></p>
                </div>
            <?php endforeach; ?>
        </body>
        </html>
        <?php
    }    
}
