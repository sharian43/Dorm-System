<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="../Presentation/css\maint.css">
</head>
<body>
  <h2>Admin Panel - Submitted Issues</h2>

<?php
// namespace BusinessLogic;

// use BusinessLogic;
// use Users;

require "../Data Access/DBControllerM.php";
// Read and parse submitted issues from the file
  $file = 'submitted_issues.txt';

  if (file_exists($file)) {
    $submittedIssues = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!empty($submittedIssues)) {
      echo "<ul>";
      foreach ($submittedIssues as $issue) {
        // Split the line to retrieve issue description and submission time
        list($submissionTime, $issueDescription) = explode(']', $issue, 2);
        $submissionTime = trim(substr($submissionTime, 1));
        $issueDescription = trim($issueDescription);

        // Display the submitted issue in a list item
        echo "<li>";
        echo "<strong>Submission Time:</strong> " . htmlspecialchars($submissionTime) . "<br>";
        echo "<strong>Issue Description:</strong> " . htmlspecialchars($issueDescription) . "<br>";
        echo "</li>";
        echo "<br>";
      }
      echo "</ul>";
    } else {
      echo "<p>No submitted issues yet.</p>";
    }
  } else {
    echo "<p>No submitted issues yet.</p>";
  }
  ?>
</body>
</html>
