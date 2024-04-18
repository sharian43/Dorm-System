<?php

namespace BusinessLogic;

use DataAccess;

class Request
{
    public $issue;
    public $userID;

    public function __construct($issue, $userID)
    {
        $this->issue = $issue;
        $this->userID = $userID;
    }
    public function getUserID()
    {
        return $this->userID;
    }
    public function getIssue()
    {
        return $this->issue;
    }
}
