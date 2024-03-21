<?php
class Resident extends User{
    public $id;
    public $firstname;
    public $lastname;
    public $password;
    public $timeslotamt;

    public function __construct($id, $firstname, $lastname, $password, $timeslotamt) {
        parent::__construct($id, $firstname, $lastname, $password);
        $this->timeslotamt = $timeslotamt;
    }
    public function getTimeslot(){
        return $timeslotamt;
    }

}
?>