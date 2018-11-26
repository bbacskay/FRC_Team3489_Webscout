<!DOCTYPE html>
<html>
<body>

<?php
$servername = "localhost";
$username = "scoutingapp";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>

</body>
</html>