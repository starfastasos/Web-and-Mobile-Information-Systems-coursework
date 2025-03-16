<?php
session_start();

include 'db.php'; //include the database 

//initialize error messages for login and registration
$loginError = '';
$registerError = '';

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

//check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //handle login form submission
    if (isset($_POST['login'])) {

        //sanitize and retrieve the username and password from POST data
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
        
        //prepare SQL query to select the user with the given username
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        //check if exactly one user is found
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            //verify the provided password against the stored hash
            if (password_verify($password, $user['password'])) {
                //set session variables for the user 
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['lastname'] = $user['lastname'];
                $_SESSION['email'] = $user['email'];
                
                //redirect user based on the 'redirect' parameter in GET request
                if (isset($_GET['redirect']) && $_GET['redirect'] === 'CreateListing') {
                    header("Location: CreateListing.php");
                    exit();
                } elseif (isset($_GET['redirect']) && $_GET['redirect'] === 'Book' && isset($_GET['id'])) {
                    $listing_id = $_GET['id'];
                    header("Location: Book.php?id=$listing_id");
                    exit();
                } else {
                    header('Location: Feed.php');
                    exit();
                }
            } else {
                $loginError = 'Incorrect password. Please try again.'; //set error message 
            }
        } else {
            $loginError = 'Username not found. Please try again.'; //set error message 
        }

    //handle registration form submission
    } elseif (isset($_POST['register'])) {

        //sanitize and retrieve the registration form data
        $firstname = sanitize($_POST['firstname']);
        $lastname = sanitize($_POST['lastname']);
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
        $email = sanitize($_POST['email']);
        
        //validate the registration form data
        if (!preg_match("/^[a-zA-Z]+$/", $firstname)) {
            $registerError = 'First name should only contain letters.';
        } elseif (!preg_match("/^[a-zA-Z]+$/", $lastname)) {
            $registerError = 'Last name should only contain letters.';
        } elseif ((strlen($password) < 4) || (strlen($password) > 10) || (!preg_match("/[0-9]/", $password))) {
            $registerError = 'Password must be 4-10 characters long and include at least one number.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $registerError = 'Please enter a valid email address.';
        } else {
            //prepare SQL query to check if username or email already exists
            $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            //check if no user exists with the same username or email
            if ($result->num_rows === 0) {
                //hash the password and prepare SQL query to insert the new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (firstname, lastname, username, password, email) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssss', $firstname, $lastname, $username, $hashed_password, $email);
                
                //execute the insert query and handle success or failure
                if ($stmt->execute()) {
                    header('Location: Login_Register.php?register_success=1');
                    exit();
                } else {
                    $registerError = 'Registration failed. Please try again.';
                }
            } else {
                $registerError = 'Username or email already exists. Please choose another one.'; //set error message
            }
        }
    }
}

//store the registration error message in the session or unset it if no error
if ($registerError) {
    $_SESSION['register_error'] = $registerError;
} else {
    unset($_SESSION['register_error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DS_Estate - Login / Register</title>
    <link rel="stylesheet" type="text/css" href="styleNavbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="styleFooter.css">
    <link rel="stylesheet" type="text/css" href="styleLogin_Register.css">
    <script src="validation.js" defer></script>
</head>
<body>
    <div class="container">
        <?php include 'navbar.php'; ?>
        <main>
            <div class="form">
                <div id="loginForm" style="display: block;">
                    <h2>Login</h2>
                    <?php if ($loginError): ?>
                        <p class="error"><?php echo $loginError; ?></p>
                    <?php endif; ?>
                    <?php if (isset($_GET['register_success'])): ?>
                        <p class="success">Registration successful! Please log in.</p>
                    <?php endif; ?>
                    <!-- login form -->
                    <form action="Login_Register.php<?php echo $_SERVER['QUERY_STRING'] ? '?' . htmlspecialchars($_SERVER['QUERY_STRING']) : ''; ?>" method="post">
                        <label for="loginUsername">Username:</label>
                        <input type="text" id="loginUsername" name="username" autocomplete="off" required>
                        <br>
                        <label for="loginPassword">Password:</label>
                        <input type="password" id="loginPassword" name="password" autocomplete="off" required>
                        <br>
                        <button type="submit" name="login">Login</button>
                    </form>
                    <br>
                    <p>You don't have an account? <button type="button" onclick="showForm('register')">Register</button></p>
                </div>
                <div id="registrationForm" style="display: none;">
                    <h2>Register</h2>
                    <?php if ($registerError): ?>
                        <p class="error"><?php echo $registerError; ?></p>
                    <?php endif; ?>
                    <!-- registration form -->
                    <form action="Login_Register.php<?php echo $_SERVER['QUERY_STRING'] ? '?' . htmlspecialchars($_SERVER['QUERY_STRING']) : ''; ?>" method="post">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" autocomplete="off" required>
                        <br>
                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" autocomplete="off" required>
                        <br>
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" autocomplete="off" required>
                        <br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" autocomplete="off" required>
                        <br>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" autocomplete="off" required>
                        <br>
                        <button type="submit" name="register">Register</button>
                    </form>
                    <br>
                    <p>You have an account? <button type="button" onclick="showForm('login')">Login</button></p>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const loginForm = document.getElementById('loginForm');
            const registrationForm = document.getElementById('registrationForm');

            //function to show either the login form or registration form based on the button press
            window.showForm = function (formType) {
                if (formType === 'login') {
                    loginForm.style.display = 'block';
                    registrationForm.style.display = 'none';
                } else {
                    loginForm.style.display = 'none';
                    registrationForm.style.display = 'block';
                }
            };

            //if there is a registration error, show the registration form by default
            <?php if (!empty($registerError)): ?>
                showForm('register');
            <?php endif; ?>
            
        });
    </script>
</body>
</html>