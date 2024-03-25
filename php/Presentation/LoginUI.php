<?php

namespace Presentation;

use Security;

require "../Security/Authenticator.php";

class LoginUI
{
    public function __construct()
    {
    }
    public function login($username, $password)
    {
        $auth = new Security\Authenticator();
        $stored = $auth->loginUser($username, $password);
        var_dump(($username));
        if (password_verify($password, $stored['password'])) {
            var_dump($stored);
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $stored['type'];
            if ($stored['type'] == 'resident') {
                return "time";
            } else if ($stored["type"] == "maintenance") {
                return "status";
            }
        }
        return "incorrect";
    }
}


if (isset($_POST["userName"]) && isset($_POST["password"])) {
    $log = new LoginUI();
    echo $log->login($_POST["userName"], $_POST["password"]);
}
