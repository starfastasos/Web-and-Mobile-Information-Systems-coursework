<?php
session_start(); 
session_destroy(); //destroy the current session to logout the user and clear all session variables
setcookie('loggedin', '', time() - 3600, "/"); //clear the 'loggedin' cookie by setting its expiration time to one hour in the past
setcookie('username', '', time() - 3600, "/"); //clear the 'username' cookie by setting its expiration time to one hour in the past
header('Location: Login_Register.php'); //redirect the user to the Login / Register page after logging out
?>