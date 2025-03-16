<?php
session_start();  
include 'db.php'; //include database 

//use cookies to set session values if not already set
if (!isset($_SESSION['loggedin']) && isset($_COOKIE['loggedin'])) {

    //set session values from cookies if session is not already set
    $_SESSION['loggedin'] = $_COOKIE['loggedin'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['firstname'] = $_COOKIE['firstname'];
    $_SESSION['lastname'] = $_COOKIE['lastname'];
    $_SESSION['email'] = $_COOKIE['email'];
}

//redirect to Login/Register page if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: Login_Register.php');
    exit();
}

$listing_id = $_GET['id']; //get listing id 
//take user's info from session
$firstname = $_SESSION['firstname'];  
$lastname = $_SESSION['lastname'];    
$email = $_SESSION['email'];          
$showPersonalInfoForm = false;//flag to determine if personal info form should be shown
//initialize variables
$error = '';  
$final_amount = 0;                    
$discount_rate = 0;                   

//process form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['check_availability'])) {

        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];

        //validate dates
        $today = date('Y-m-d');
        if ($checkin >= $checkout) {
            $error = "Check-in must be before check-out.";
        } elseif ($checkin < $today || $checkout < $today) {
            $error = "Dates cannot be in the past.";
        } else {
            //calculate number of nights
            $checkin_date = new DateTime($checkin);
            $checkout_date = new DateTime($checkout);
            $interval = $checkin_date->diff($checkout_date);
            $num_nights = $interval->days;

            //fetch listing details to calculate booking amount
            $sql = "SELECT price_per_night FROM listings WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $listing_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $listing = $result->fetch_assoc();

            $price_per_night = $listing['price_per_night'];
            $initial_amount = $price_per_night * $num_nights;

            //generate random discount rate
            $discount_rate = rand(10, 30) / 100;

            //calculate final amount after discount
            $final_amount = $initial_amount - ($initial_amount * $discount_rate);

            //check availability for selected dates
            $sql = "SELECT * FROM reservations WHERE listing_id = ? AND (checkin <= ? AND checkout >= ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iss', $listing_id, $checkout, $checkin);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "The specific dates are not available. Please select different dates.";
            } else {
                $showPersonalInfoForm = true; //show personal info form if dates are available
            }
        }
    } elseif (isset($_POST['make_reservation'])) {

        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $final_amount = $_POST['final_amount'];

        //insert reservation details into db
        $sql = "INSERT INTO reservations (listing_id, username, checkin, checkout, firstname, lastname, email, amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issssssd', $listing_id, $_SESSION['username'], $checkin, $checkout, $firstname, $lastname, $email, $final_amount);

        if ($stmt->execute()) {
            //display success message and redirect to Feed.php on successful booking
            echo '<script>alert("Booking successful!"); window.location.href = "Feed.php";</script>';
            exit();
        } else {
            $error = "Booking failed. Please try again."; //display error message 
        }
    }
}

//fetch listing details based on listing id from database
$sql = "SELECT * FROM listings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $listing_id);
$stmt->execute();
$result = $stmt->get_result();
$listing = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS_Estate - Book Listing</title>
    <link rel="stylesheet" type="text/css" href="styleNavbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="styleFooter.css">
    <link rel="stylesheet" type="text/css" href="styleBook.css">
    <script>
        //function to validate date inputs
        function validateDates() {
            var checkin = document.getElementById('checkin').value;
            var checkout = document.getElementById('checkout').value;
            var today = new Date().toISOString().split('T')[0];

            if (checkin >= checkout) {
                alert('Check-in must be before check-out.');
                return false;
            } else if (checkin < today || checkout < today) {
                alert('Dates cannot be in the past.');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <?php include 'navbar.php'; ?>  
        <h1>Booking Details</h1>
        <?php if ($error): ?>
            <script>alert("<?php echo $error; ?>");</script>  
        <?php endif; ?>
        <div class="listing">
            <img src="<?php echo $listing['image']; ?>" alt="Property Image">
            <h2><?php echo $listing['title']; ?></h2>
            <p>Location: <?php echo $listing['location']; ?></p>
            <p>Rooms: <?php echo $listing['rooms']; ?></p>
            <p>Price per Night: <?php echo $listing['price_per_night']; ?>&euro;</p>
        </div>
        <!-- availability check form -->
        <?php if (!$showPersonalInfoForm): ?>
            <form method="post" action="Book.php?id=<?php echo $listing_id; ?>" onsubmit="return validateDates()">
                <label for="checkin">Check-in Date:</label>
                <input type="date" id="checkin" name="checkin" required>
                <label for="checkout">Check-out Date:</label>
                <input type="date" id="checkout" name="checkout" required>
                <button type="submit" name="check_availability">Continue</button>
            </form>
        <?php endif; ?>
        <!-- personal info form -->
        <?php if ($showPersonalInfoForm): ?>
            <div class="payment_info">
                <p>Discount Rate: <?php echo $discount_rate * 100; ?>%</p>
                <p>Final Payment Amount: <?php echo number_format($final_amount, 2); ?>&euro;</p>
            </div>
            <form method="post" action="Book.php?id=<?php echo $listing_id; ?>">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required autocomplete="off">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required autocomplete="off">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required autocomplete="off">
                <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>">
                <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>">
                <input type="hidden" name="final_amount" value="<?php echo htmlspecialchars($final_amount); ?>">
                <button type="submit" name="make_reservation">Book Now</button>
            </form>
        <?php endif; ?>
        <?php include 'footer.php'; ?>  
    </div>
</body>
</html>