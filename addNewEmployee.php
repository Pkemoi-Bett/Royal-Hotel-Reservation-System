<?php
session_start();

require_once './conn.php';

if (!isset($_SESSION['admin_id'])) {
    echo "<script>
        alert('You must login as an admin to access this page.');
        window.location.href='./adminLogin.php';
        </script>";
    exit();
}

if (isset($_POST['submit'])) {
    $username = $_POST['uname'];
    $email = $_POST['email'];
    $password = $_POST['psw'];
    $rPassword = $_POST['psw-repeat'];

    // Validate passwords
    if ($password !== $rPassword) {
        $message[] = 'Password Mismatch!';
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM `admin` WHERE admin_email = ? OR admin_name = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message[] = 'Username or email already exists';
        } else {
            // Hash password securely
            $hashpsw = password_hash($password, PASSWORD_BCRYPT);

            // Insert new admin data
            $stmt = $conn->prepare("INSERT INTO `admin` (`admin_name`, `admin_email`, `admin_password`) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashpsw);

            if ($stmt->execute()) {
                // Registration successful
                echo "<script>
                alert('Employee added successfully.');
                window.location.href='./adminDashboard.php#employee';
                </script>";
            } else {
                // Registration failed
                $message[] = 'Error adding the employee. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/register.css">
    <script src="./js/validate.js"></script>
</head>

<?php include "./partials/header.php" ?>

<body>
    <div class="outline">
        <form action="" method="POST" name="rForm" enctype="multipart/form-data" class="container">
            <h1>Add Employee</h1>
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '<div class="message">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</div>';
                }
            }
            ?>
            <span id="validate"></span>
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" id="uname" name="uname" required>

            <label for="email"><b>Email</b></label>
            <input type="email" placeholder="Enter Email" id="email" name="email" required>

            <label for="psw"><b>New Password</b></label>
            <input type="password" placeholder="Enter Password" id="psw" name="psw" required>

            <label for="psw-repeat"><b>Repeat Password</b></label>
            <input type="password" placeholder="Repeat Password" name="psw-repeat" required>

            <input type="submit" id="register" name="submit" value="Add Employee" onclick="return validate()">
        </form>
    </div>
</body>

<?php include "./partials/footer.php" ?>

</html>
