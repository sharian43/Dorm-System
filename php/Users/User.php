<?php

namespace Users;

class User
{
    private $id;
    private $firstname;
    private $lastname;
    private $password;

    public function __construct($id, $firstname, $lastname)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->firstname;
    }

    public function LastName()
    {
        return $this->lastname;
    }
}
