<?php
require_once 'ReservationSystem.php';
class MachineReservation{
    //Initializing variables
    private $reservationSystem;

    public function __construct(ReservationSystem $reservationSystem) {
        $this->reservationSystem = $reservationSystem;
    }
   
    public function showDaySelector($selectedDay) {
        $daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        echo '<div id="daySelector">';
        foreach($daysOfWeek as $index => $day) {
            $isSelectable = ($index == $selectedDay);
            echo '<div class="days' . ($isSelectable ? ' selected' : '') . '">' . $day . '</div>';
        }
        echo '</div>';
    }

    public function showReservationSchedule($reservations, $selectedDay) {
        echo '<div id="gridWork">';
        for ($machine = 1; $machine <= 10; $machine++) {
            $isAvailable = ($this->reservationSystem->getMachineStatus("Machine $machine") == 1);
            echo '<div class="machine">
                    <img src="' . ($isAvailable ? "img/washing.png" : "img/washingred.png") . '" alt="Laundry washing" id="machine">
                    <span>Machine ' . $machine . '</span>
                </div>';
            for ($hour = 8; $hour <= 20; $hour++) {
                $timeslot = sprintf('%02d:00:00', $hour);
                $machineKey = "Machine $machine";
                $isSelected = isset($reservations[$machineKey][$selectedDay][$timeslot]);
                $currentTime = date('H:00:00');
                $isUnavailable = (($timeslot < $currentTime && $selectedDay == date("w")) || ($hour == date('H') && $selectedDay == date("w")) || $selectedDay < date("w") || ($this->reservationSystem->getMachineStatus($machineKey) == 0));
                
                echo '<div class="timeSlot' . ($isSelected ? ' selected' : '') . ($isUnavailable ? ' unavailable' : '') . '">' . ($hour % 12 ?: 12) . ':00 ' . ($hour < 12 ? 'AM' : 'PM') . '</div>';

                if ($isUnavailable && $isSelected && $hour != date('H')) {
                    $this->reservationSystem->removeUnavailable($machineKey, $timeslot, $selectedDay);
                }
            }
        }
        echo '</div>';
    }

    

    
}
// NOT SURE ABOUT HERE
$reservationSystem = new ReservationSystem();
$reservationSchedule = new MachineReservation($reservationSystem);
$selectedDay = 0; // Example selected day index
$reservations = []; // Example reservations array
$reservationSchedule->showDaySelector($selectedDay);
$reservationSchedule->showReservationSchedule($reservations, $selectedDay);
?>