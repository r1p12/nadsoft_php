<?php
$host = "localhost";   
$user = "root";        
$pass = "";            
$db   = "nadsoft"; 
$port = 3306;          // explicitly set port

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die(" Connection failed: " . $conn->connect_error);
} else {
   
}
?>
