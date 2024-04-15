<?php

namespace DataAccess;

use mysqli;
use Security;
use Security\Authenticator;

require "../Security/Authenticator.php";

class DBController
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli("localhost", "root", "", "138users");

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
        //set date function to local time
        date_default_timezone_set('America/Jamaica');
    }

    public function getTyped(mysqli $mysqli, $username)
    {
        $query = $mysqli->prepare("SELECT firstname, lastname, usertype FROM dorm WHERE username = ?");
        if ($query) {
            $query->bind_param("s", $username);
            if ($query->execute()) {
                $query->store_result();
                if ($query->num_rows === 1) {
                    $query->bind_result($firstname, $lastname, $usertype);
                    $query->fetch();
                    $typeInformation = ['first' => $firstname, 'last' => $lastname, 'type' => $usertype];
                    $query->close();
                    return $typeInformation;
                }
            }
        }
    }


    public function submitRequest($issueDescription, $currentTime)
    {
        $currentTime = date('m-d-Y H:i:s');

        // Prepare the SQL statement
        $stmt = $this->mysqli->prepare("INSERT INTO maintenance_requests (issue_description, submission_time) VALUES (?, ?, ?, ?)" );
        $stmt->bind_param("iiss",$issueDescription, $currentTime);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getAllRequests()
    {
        $requests = [];
        $file = 'submitted_issues.txt';

        // Check if the file exists
        if (file_exists($file)) {
            // Read the file line by line
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {
                $parts = explode(': ', $line);
                if (isset($parts[1])) {
                    // Extract issue description and submission time
                    $issueDescription = $parts[0];
                    $submissionTime = $parts[1];

                    $request = [
                        'issue_description' => $issueDescription,
                        'submission_time' => $submissionTime
                    ];
                    $requests[] = $request;
                }
            }
        }

        return $requests;
    }

    public function closeConnection()
    {
        $this->mysqli->close();
    }
}
?>
