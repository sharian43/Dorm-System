<?php
class Machine{
  private $machine;
  private $status;

   public function __construct($machine,$status){
      $this->$machine=$machine;
      $this->$status=$status;
   }
  public function getMachine(){
      return $this->machine; 
   }
   public function getStatus(){
      return $this->status;
   }
  
}












?>
