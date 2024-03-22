<?php

Class Timeslot{
    private $day;
    private $machineNum;
    private $time;
    private $resident;

    function __construct($day, $machineNum, $time){
        $this->day = $day;
        $this->machineNum = $machineNum;
        $this->time = $time;
    }

    public function getResident(){
        return $this->resident;
    }

    public function setResident(Resident $resident){
        $this->resident = $resident;
    }

    public function getDay(){
        return $this->day;
    }
    public function getMachineNum(){
        return $this->machineNum;
    }
    public function getTime(){
        return $this->time;
    }
}