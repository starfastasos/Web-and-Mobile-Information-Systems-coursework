<?php
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "ds_estate"; //database name 

//create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

//check connection
if ($conn->connect_error) {
    //if connection fails, terminate the script and display an error message
    die("Connection failed: " . $conn->connect_error);
}
?>