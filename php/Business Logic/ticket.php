<?php
namespace BusinessLogic;
use DataAccess;
class Ticket{
  private $time;
  private $status;

   public function __construct($time,$status){
      $this->$time=$timw;
      $this->$status=$status;
   }
  public function getTime(){
      return $this->time; 
   }
   public function getStatus(){
      return $this->status;
   }
  
}












?>
