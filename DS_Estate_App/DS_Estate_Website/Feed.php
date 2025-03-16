<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS_Estate - Feed</title>
    <link rel="stylesheet" type="text/css" href="styleNavbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="styleFooter.css">
    <link rel="stylesheet" type="text/css" href="styleFeed.css">
</head>
<body>
    <div class="container">
        
        <?php 
        session_start();  
        include 'db.php'; //include database connection file

        //use cookies to set session values if not already set
        if (!isset($_SESSION['loggedin']) && isset($_COOKIE['loggedin'])) {
            $_SESSION['loggedin'] = $_COOKIE['loggedin'];
            $_SESSION['username'] = $_COOKIE['username'];
        }

        $sql = "SELECT * FROM listings"; //SQL query to fetch all listings
        $result = $conn->query($sql); //execute query and get result object
        ?>

        <?php include 'navbar.php'; ?>  
        <h1>Available Listings</h1>
        <div class="listings">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="listing">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Property Image">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p>Location: <?php echo htmlspecialchars($row['location']); ?></p>
                    <p>Rooms: <?php echo htmlspecialchars($row['rooms']); ?></p>
                    <p>Price per Night: <?php echo htmlspecialchars($row['price_per_night']); ?>&euro;</p>
                    <button onclick="redirectToBooking(<?php echo $row['id']; ?>)">Book Now</button>
                </div>
            <?php endwhile; ?>
        </div>
        <?php include 'footer.php'; ?>  
    </div>
    <script>
        function redirectToBooking(listing_id) {
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                window.location.href = 'Book.php?id=' + listing_id; //redirect to Book page if user is logged in
            <?php else: ?>
                window.location.href = 'Login_Register.php?redirect=Book&id=' + listing_id; //redirect to Login / Register page 
            <?php endif; ?>
        }
    </script>
</body>
</html>