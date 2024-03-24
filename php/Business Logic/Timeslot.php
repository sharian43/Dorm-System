<?php

namespace BusinessLogic;

use DataAccess;

class Timeslot
{
    private $day;
    private $machineNum;
    private $time;
    private $resident;

    function __construct($day, $machineNum, $time, $resident)
    {
        $this->day = $day;
        $this->machineNum = $machineNum;
        $this->time = $time;
        $this->resident = $resident;
    }

    public function getResident()
    {
        return $this->resident;
    }

    public function getDay()
    {
        return $this->day;
    }
    public function getMachineNum()
    {
        return $this->machineNum;
    }
    public function getTime()
    {
        return $this->time;
    }
}
