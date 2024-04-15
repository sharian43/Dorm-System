<?php

class AuthenticateController
{
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
            <div class="ticketBox">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="container">
                        <h2>Queue Ticket</h2>
                        <div class="machine">
                            <div id="user">
                                <h3 id="id">User ID: <?= $username ? $username : 'Admin' ?></h3>
                                <h3 id="date">Day: <?= $reservation['day'] ?></h3>
                            </div>
                            <p id="ticket"><strong>Ticket ID:</strong></p>
                            <p id="dec1">********************************************</p>
                            <h3 id="ticket-val">A<?= $reservation['id'] ?></h3>
                            <p id="dec2">********************************************</p>
                            <h4>Description:</h4>
                            <p id="details"><strong>Time Slot:</strong> <?= $reservation['timeslot'] ?><br>
                                <strong>Period:</strong> 60 mins<br>
                                <strong>Service:</strong> Wash, Rinse & Dry<br>
                                <strong>Selected Equipment:</strong> <?= $reservation['machine'] ?></p>
                            <p id="message">Tip: Please arrive 5 minutes prior to your scheduled time!</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </body>
        </html>
        <?php
    }
}
