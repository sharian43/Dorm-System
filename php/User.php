<?php
class User {
    public $id;
    public $firstname;
    public $lastname;
    public $password;

    public function __construct($id, $firstname, $lastname, $password) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
    }
    public function getId(){
        return $id;
    }

    public function getFirstName(){
        return $firstname
    }

    public function LastName(){
        return $lastname
    }
   
    public function getPassword(){
        return  $password
    }
}


?>