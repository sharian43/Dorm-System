<?php
class Resident extends User{
    private $timeslotamt;

    public function __construct($id, $firstname, $lastname) {
        parent::__construct($id, $firstname, $lastname);
    }
    public function getLimits(){
        return $this->timeslotamt;
    }

    public function setTimeslotamt($timeslotamt){
        $this->timeslotamt = $timeslotamt;
    }
}
