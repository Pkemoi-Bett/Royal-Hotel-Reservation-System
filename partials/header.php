<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/reset.css">
</head>

<!-- Navigation Bar -->
<nav class="navBar" style="background-color: #2AAA8A;">
    <h1 class="site-logo"><a href="./index.php">SweetHome</a></h1>
    <ul >
        <li><a href="./about.php" >About Us</a></li>
        <li><a href="./hotel.php">Restaurant</a></li>
        <li><a href="./contact.php">Contact Us</a></li>
    </ul>
    <div class="btn">
        <?php
        // Check if there is a user logged in (using a session)
        if (isset($_SESSION['username'])) {
            // Display the user's username and a logout button
            echo '<button class="username"><a href="./userDashboard.php">';
            echo $_SESSION['name'] . '</button>';
            echo '<button class="logoutbtn"><a href="./logout.php">Logout</a></button>';
        } elseif (isset($_SESSION['ownername'])) {
            // Display the user's username and a logout button
            echo '<button class="username"><a href="./ownerDashboard.php">';
            echo $_SESSION['name'] . '</button>';
            echo '<button class="logoutbtn"><a href="./logout.php">Logout</a></button>';
        } elseif (isset($_SESSION['adminname'])) {
            // Display the user's username and a logout button
            echo '<button class="username"><a href="./adminDashboard.php">';
            echo $_SESSION['adminname'] . '</button>';
            echo '<button class="logoutbtn"><a href="./logout.php">Logout</a></button>';
        } else {
            // If no user is logged in, display login and register buttons
            echo '<button class="loginBtn"><a href="./login.php">Login</a></button>';
            echo '<button class="signBtn"><a href="./register.php">Register</a></button>';
        }
        ?>
    </div>
</nav>
</html>