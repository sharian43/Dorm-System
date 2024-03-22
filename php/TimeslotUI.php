<?php
require 'TimeslotController.php';
Class TimeslotUI{
    private $machineNum;
    function __construct() {
        if (isset($_POST['machineNum'])){
            $machineNum = $_POST['machineNum'];
            $this->machineNum = $machineNum;
        }
        
    }

    public function showMachineStatus($machineKey){
        $controller = new TimeslotController();
        return $controller->fetchStatus($machineKey);
    }
    public function removeTimePassed($machineKey,$timeslot,$selectedDay){
        $controller = new TimeslotController();
        return $controller->timeslotRemover($machineKey,$timeslot,$selectedDay);
    }
    public function assignTime($machineKey,$timeslot,$selectedDay){
        $controller = new TimeslotController();
        return $controller->assignTimeslot($machineKey,$timeslot,$selectedDay);
    }

    public function reservationsForDay($selectedDay){
        $controller = new TimeslotController();
        return $controller->dailyReservations($selectedDay);
    }

    public function reservationsForcheck($selectedDay){
        $controller = new TimeslotController();
        return $controller->checkingReservations($selectedDay);
    }

} 

$handle =  new TimeslotUI();

if (isset($_POST['timeslot'],$_POST['machine'],$_POST['selectedDay'])){
    $handle->assignTime($_POST['$machine'],$_POST['$timeslot'],$_POST['selectedDay']);
}

elseif (isset($_POST['selectedDay'])){
    echo $handle->reservationsForDay($_POST['selectedDay']);
}


