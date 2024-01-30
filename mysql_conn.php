<?php
//Connection Parameters
$servername = 'localhost';
$username = 'root';
$userpwd = '';
$dbname = 'assignment1'; 
$port = "3307"; 

// Create connection
$conn = new mysqli($servername, $username, $userpwd, $dbname, $port);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);	
}
?>
