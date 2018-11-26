<?php

function CreateNewTeam($team_number, $team_name) {


    $servername = "localhost";
    $username = "scoutingapp";
    $password = "team3489";

// Create connection
    $conn = new mysqli($servername, $username, $password);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $dbstatus = "Connected successfully";
$team_number = $_POST['team_number'];
$team_name = $_POST['team_name'];

$sql = "INSERT INTO teams (team_number, team_name)
VALUES ($team_number, $team_name)";

if ($conn->query($sql) === true) {
   return "New record created successfully";
} else {
    return "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();



?>