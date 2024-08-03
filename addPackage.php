<?php
session_start();

require_once './conn.php';

// Ensure the user is logged in
if (!isset($_SESSION['owner_id'])) {
    echo "<script>
        alert('Before adding a hotel, you must log in.');
        window.location.href='./login.php';
        </script>";
    exit();
}

$owner_id = $_SESSION['owner_id'];

// Fetch hotels for the dropdown menu
$query = "SELECT `hotel_id`, `hotel_name` FROM `hotel` WHERE owner_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_POST['submit'])) {
    $hotelName = $_POST['hotel'];
    $packagename = $_POST['packageName'];
    $price = $_POST['price'];

    // Retrieve the hotel ID based on the selected hotel name
    $query = "SELECT `hotel_id` FROM `hotel` WHERE `hotel_name` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $hotelName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hotelid = $row['hotel_id'];

    $info = pathinfo($_FILES['image']['name']);
    $ext = $info['extension'];
    $imgname = $packagename . "." . $ext;

    // Validate file size and MIME type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_file_size = 2 * 1024 * 1024; // 2 MB

    if ($_FILES['image']['size'] > $max_file_size) {
        echo "<script>
            alert('Image size exceeds 2 MB. Please upload a smaller image.');
            window.location.href='./addPackage.php';
            </script>";
        exit();
    }

    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        echo "<script>
            alert('Invalid image type. Only JPEG, PNG, and GIF are allowed.');
            window.location.href='./addPackage.php';
            </script>";
        exit();
    }

    // Ensure the upload directory exists and is writable
    $upload_dir = 'assets/package/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $imgname)) {
        // Insert the new package into the database
        $insert = "INSERT INTO `package` (`package_name`, `price`, `hotel_id`, `image`) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("sdis", $packagename, $price, $hotelid, $imgname);

        if ($stmt->execute()) {
            echo "<script>
                alert('🎉 Package added successfully.');
                window.location.href='./ownerDashboard.php';
                </script>";
        } else {
            // Insertion failed
            echo "<script>
                alert('Error adding the package. Please try again.');
                window.location.href='./addPackage.php';
                </script>";
        }
    } else {
        // Image upload failed
        echo "<script>
            alert('Error uploading the image. Please try again.');
            window.location.href='./addPackage.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Package</title>
    <link rel="stylesheet" href="./css/addhotel.css">
</head>

<?php include "./partials/header.php" ?>

<body>
    <div class="main">
        <h1>Add Package</h1>
        <form action="addPackage.php" method="POST" enctype="multipart/form-data">
            <label for="packageName">Package Name:</label>
            <input type="text" id="packageName" name="packageName" required>

            <label for="price">Package Price:</label>
            <input type="number" id="price" name="price" required>

            <label for="image">Package Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <label for="hotel">Select Hotel:</label>
            <select id="hotel" name="hotel" required>
                <option value="" disabled selected>Select Hotel</option>
                <?php
                while ($row = $result->fetch_assoc()) {
                    $name = htmlspecialchars($row['hotel_name'], ENT_QUOTES, 'UTF-8');
                    echo "<option value=\"$name\">$name</option>";
                }
                ?>
            </select>

            <input type="submit" name="submit" value="Add Package">
        </form>
    </div>

    <?php include "./partials/footer.php" ?>
</body>

</html>
