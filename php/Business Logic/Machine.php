<?php

namespace BusinessLogic;

use DataAccess;

class Machine
{
   public $machineID;
   public $status;

   public function __construct($machineID, $status)
   {
      $this->machineID = $machineID;
      $this->status = $status;
   }
   public function getMachineID()
   {
      return $this->machineID;
   }
   public function getStatus()
   {
      return $this->status;
   }
}
