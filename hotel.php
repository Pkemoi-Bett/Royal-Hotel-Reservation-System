<?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/hotel.css">
</head>

<?php include "./partials/header.php" ?>

<body>
    <div class="main">
        <div class="title">
            <h1>RESTAURANTS</h1>
        </div>

        <div class="search-container">
            <input type="text" id="hotelSearch" placeholder="Search for Restaurant...">
            <button onclick="searchrestaurant()">Search</button>
        </div>

        <div class="cardContainer">
    <?php
    
    require_once './conn.php';
   
    $query = "SELECT * FROM `hotel`";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['hotel_id'];
            $name = $row['hotel_name'];
            $imgname = $row['image'];

            echo '<div class="card">';
            echo '<img src="./assets/images/' . $imgname . '" alt="hotel" style="object-fit: cover; width: 100%; height: 100%;">';
            echo '<h1>' . $name . '</h1>';
            echo '<button onclick="location.href=\'./booking.php?hotel=' . $id . '\'" type="button">Book Now</button>';
            echo '</div>';
        }
    }

    ?>
</div>
    </div>
</body>

<script src="./js/search.js"></script>
<?php include "./partials/footer.php" ?>

</html>