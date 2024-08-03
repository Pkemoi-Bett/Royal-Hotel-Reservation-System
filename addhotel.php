<?php
session_start();
require_once './conn.php';

// Redirect to login page if the owner is not logged in
if (!isset($_SESSION['owner_id'])) {
    echo "<script>
        alert('Please login before adding a hotel.');
        window.location.href='./login.php';
        </script>";
    exit();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $hotel_name = mysqli_real_escape_string($conn, $_POST['hotelName']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $owner_id = $_SESSION['owner_id'];
    $info = pathinfo($_FILES['image']['name']);
    $ext = $info['extension'];
    $imgname = $hotel_name . "." . $ext;
    $target = 'assets/images/' . $imgname;

    // Check for upload errors
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error_message = 'An error occurred while uploading the image. Error code: ' . $_FILES['image']['error'];
        echo "<script>
            alert('$error_message');
            window.location.href='./addhotel.php';
            </script>";
        exit();
    }

    // Check if the image is uploaded successfully
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $insert = "INSERT INTO `hotel` (`hotel_name`, `description`, `image`, `owner_id`) VALUES ('$hotel_name', '$description', '$imgname', '$owner_id')";
        $result = mysqli_query($conn, $insert);

        if ($result) {
            echo "<script>
                alert('🎉 Hotel added successfully.');
                window.location.href='./ownerDashboard.php';
                </script>";
        } else {
            echo "<script>
                alert('Error adding the hotel. Please try again.');
                window.location.href='./addhotel.php';
                </script>";
        }
    } else {
        echo "<script>
            alert('Error uploading the image. Please check directory permissions.');
            window.location.href='./addhotel.php';
            </script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hotel</title>
    <link rel="stylesheet" href="./css/addhotel.css">
</head>
<body>
    <?php include "./partials/header.php"; ?>
    <div class="main">
        <h1>Add Hotel</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="hotelName">Hotel Name:</label>
            <input type="text" id="hotelName" name="hotelName" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="image">Hotel Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <input type="submit" name="submit" value="Add Hotel">
        </form>
    </div>
    <?php include "./partials/footer.php"; ?>
</body>
</html>
