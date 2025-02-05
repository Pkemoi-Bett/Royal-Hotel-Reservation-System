<?php
session_start();
?>

<?php
// Include the database connection script
require_once "./conn.php";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['msg'];

    // Insert user data into the database
    $insert = mysqli_query($conn, "INSERT INTO `contact` (`sender_Name`, `email`, `phone`, `message`) VALUES ('$name', '$email', '$phone', '$message')");
    
    if ($insert) {
        //  successful
        echo
        "<script>
        alert(' 🎉 Message Succesfully Submited.. .');
        window.location.href='./contact.php';
        </script>";
    } else {
        // failed
        echo
        "<script>
        alert(' Something went wrong Try Again.. .');
        window.location.href='./contact.php';
        </script>";
    }
}

// close the connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SweetHome: Contact Us</title>
    <!-- Include CSS file -->
    <link rel="stylesheet" href="css/contact.css">
    <script src="./js/email.js"></script>
</head>

<!-- Include header -->
<?php include "./partials/header.php" ?>

<body>
    <div class="main">
        <div class="ConnOuntline">
            <div class="container">


                <h1>Contact Us</h1>

                <p class="para">Discover how "SweetHome" can assist you.</p>
                <br>
                <p>We believe in open communication and are excited to connect with our visitors. Your feedback is important to us, and we appreciate your interest. We're here to help in any way we can. Simply fill out our user-friendly online form below, providing the necessary details.</p>


                <div class="contactForm">

      <span id="validate"> </span> <br>

    <form action="contact.php" method="POST" name="cForm" enctype="multipart/form-data" onsubmit="return validateForm()">

                        <lable for="name"><b>Name</b></lable>
                        <input class="inputbox" type="text" name="name" placeholder="Enter Name" required>

                        <label for="email"><b>Email</b></label>
                        <input class="inputbox" type="email" name="email" placeholder="abc@example.com" required>

                        <lable for="phone"><b>Phone Number</b></lable>
                        <input class="inputbox" type="phone" name="phone" placeholder="Enter Phone Number" required>

                        <label for="message"><b>Type Your Message</b></label>
                        <textarea class="tarea" name="msg" placeholder="Your Message" required></textarea>

                        <input type="submit" id="sendButton" name="submit" value="Send">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- Include footer -->
<?php include "./partials/footer.php" ?>

</html>