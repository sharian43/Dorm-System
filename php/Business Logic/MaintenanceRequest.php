<?php
namespace BusinessLogic;

use Presentation;
use Users;

require "../Data Access/DBControllerM.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $submissionTime = date('m/d/Y, h:i:s');
  $issueDescription = $_POST["issue"];

  $file = 'submitted_issues.txt';
  file_put_contents($file, "[$submissionTime]  $issueDescription" . PHP_EOL, FILE_APPEND);

  echo "success";
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Maintenance Request</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Presentation/css\maint.css"/>
    <script src="../Presentation/js\maint.js"></script>
</head>
<body>
    <div id="maintenanceBody">
    <h1>138 Dorm Laundry System</h1>
    <h2>Maintenance Request Form</h2>
    <!-- Form for users to submit issues -->
    <div id="requestForm">
      <p>Describe the issue:</p>
      <textarea id="issue_description" name="issue_description" rows="4" cols="50" required></textarea><br>
      <button type="submit" onclick="submitRequest()">Submit Request</button>
    </div>
    <!-- Display confirmation message using JavaScript if the form was submitted -->
    <p id="confirmationMessage" style="display: none;">Request was submitted!</p>
  </div>
</body>
</html>


