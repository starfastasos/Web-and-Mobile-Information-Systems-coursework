<?php
session_start();  

//redirect to Login/Register page if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: Login_Register.php'); //redirect user to Login/Register page
    exit();
}

include 'db.php'; //include database 

$error = ''; //initialize error message variable

//process form submission if POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //sanitize and validate inputs
    $title = htmlspecialchars(trim($_POST['title']));  
    $location = htmlspecialchars(trim($_POST['location']));  
    $rooms = htmlspecialchars(trim($_POST['rooms']));  
    $price_per_night = htmlspecialchars(trim($_POST['price_per_night']));  
    $image_dir = 'images/listings/'; //directory where images will be stored
    $image = $image_dir . basename($_FILES['image']['name']); //full path of the image file

    //check if image file is an actual image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        $error = 'File is not an image.';
    }

    //validate title format 
    if (!preg_match('/^[a-zA-Z ]+$/', $title)) {
        $error = 'Title must contain only characters and spaces.';
    }

    //validate location format 
    if (!preg_match('/^[a-zA-Z ]+$/', $location)) {
        $error = 'Location must contain only characters and spaces.';
    }

    //validate rooms 
    if (!is_numeric($rooms)) {
        $error = 'Rooms must be a integer.';
    }

    //validate price_per_night 
    if (!is_numeric($price_per_night)) {
        $error = 'Price per Night must be a integer.';
    }

    //if no validation errors, proceed to upload image and insert into db
    if (empty($error)) {

        //upload file to server
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image)) {

            //insert listing details into database
            $sql = "INSERT INTO listings (image, title, location, rooms, price_per_night) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssss', $image, $title, $location, $rooms, $price_per_night);

            if ($stmt->execute()) {
                header('Location: Feed.php'); //redirect to Feed page after successful listing creation
                exit();
            } else {
                $error = 'Failed to create listing. Please try again.'; //db execution failed
            }
        } else {
            $error = 'Failed to upload image.'; //image upload failed
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS_Estate - Create Listing</title>
    <link rel="stylesheet" type="text/css" href="styleNavbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="styleFooter.css">
    <link rel="stylesheet" type="text/css" href="styleCreateListing.css"> 
</head>
<body>
    <div class="container">
        <?php include 'navbar.php'; ?> 
        <h1>Create Listing</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>  
        <?php endif; ?>
        <form action="CreateListing.php" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" pattern="[a-zA-Z ]+" required autocomplete="off">
            <br>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" pattern="[a-zA-Z ]+" required autocomplete="off">
            <br>
            <label for="rooms">Rooms:</label>
            <input type="number" id="rooms" name="rooms" required autocomplete="off">
            <br>
            <label for="price_per_night">Price per Night:</label>
            <input type="number" id="price_per_night" name="price_per_night" required autocomplete="off">
            <br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required autocomplete="off">
            <br>
            <button type="submit">Create Listing</button>  
        </form>
        <?php include 'footer.php'; ?>  
    </div>
</body>
</html>