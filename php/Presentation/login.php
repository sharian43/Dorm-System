<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="css\login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans:wght@600&family=Roboto:ital@0;1&display=swap" rel="stylesheet">
    <title>Laundry Link</title>
    <script src="js\login.js"></script>

</head>

<body>
    <!--user login form that accepts login information and post it to the UserAuthentication file-->
    <form method="post" action="loginUI.php" onsubmit="Login(event)">
        <div id="loginMenu">
            <h1>Sign In</h1>
            <input type="text" name="userName" class="entry" placeholder="Username">
            <input type="password" name="password" class="entry" placeholder="Password">
            <button id="login" name="submit" type="submit">Sign In</button>
        </div>
    </form>
</body>

</html>