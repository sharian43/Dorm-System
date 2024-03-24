<?php

namespace Users;

class Staff extends User
{
    public $id;
    public $firstname;
    public $lastname;
    public $password;
    public $timeslotamt;

    public function __construct($id, $firstname, $lastname)
    {
        parent::__construct($id, $firstname, $lastname);
    }
}
