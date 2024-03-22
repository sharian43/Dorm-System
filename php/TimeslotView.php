<!DOCTYPE html>
<html lang="en">
<?php
require 'TimeslotUI.php';
$TimeslotUI = new TimeslotUI(); 
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>138 Dorm Laundry System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css\laundry.css" />
    <script src="js\time.js"></script>
</head>

<body>
    <div id="timeSlotField">
        <div id="topBar">
            <img src="img\Menu Button.png" alt="menu button" id="menu">
            <span id="currTime"></span>
        </div>
        <div id="daySelector">
            <?php
            $selectedDay = date("w");
            $dayz = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            foreach ($dayz as $index => $day) :
                $isSelectable = ($index == $selectedDay);
            ?>
                <div class="days<?= $isSelectable ? ' selected' : '' ?>">
                    <?= $day ?>
                </div>
            <?php endforeach; ?>
        </div>
        <!--Sets up the reservation schedule timeslots checking for if a slot is selected from database and if its time is passed and displaying it to the user -->
        <div id="gridWork">
            <?php for ($machine = 1; $machine <= 10; $machine++) : ?>
                <div class="machine">
                    <?php $isAvailable = ($TimeslotUI->showMachineStatus("Machine $machine") == 1) ?>
                    <img src="<?= $isAvailable ? "img\washing.png" : "img\washingred.png" ?>" alt="Laundry washing" id="machine">
                    <span>Machine <?= $machine; ?></span>
                </div>
                <?php
                $reservations = $TimeslotUI->reservationsForcheck($selectedDay);
                ?>
                <?php for ($hour = 8; $hour <= 20; $hour++) : ?>
                    <?php
                        $timeslot = sprintf('%02d:00:00', $hour);
                        $machineKey = "Machine $machine";
                        $isSelected = isset($reservations[$machineKey][$selectedDay][$timeslot]);
                        $currentTime = date('H:00:00');
                        $isUnavailable = (($timeslot < $currentTime && $selectedDay == date("w")) || ($hour == date('H') && $selectedDay == date("w")) || $selectedDay < date("w") || ($TimeslotUI->showMachineStatus($machineKey) == 0));
                    ?>
                    <!-- Set selected if userID is set in the database and add class for unavailable timeslots if current time passes it-->
                    <div class="timeSlot<?= $isSelected ? ' selected' : '' ?><?= $isUnavailable ? ' unavailable' : '' ?>">
                        <?= $hour % 12 ?: 12 ?>:00 <?= $hour < 12 ? 'AM' : 'PM' ?>
                    </div>
                    <?php

                    if ($isUnavailable && $isSelected && $hour != date('H')) {
                        $TimeslotUI->removeTimePassed($machineKey, $timeslot, $selectedDay);
                    }

                    ?>
                <?php endfor; ?>
            <?php endfor; ?>
        </div>
    </div>
</body>

</html>