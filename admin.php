<?php include 'includes/header.php'?>
<?php include 'includes/classes.php' ?>
<!-- Begin Main Content -->

  <body>
  <?php
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
?>

    <div class="container">

      <div class="starter-template">
        <h1>Administration</h1>
        <p class="lead">This page will be used for configuration.</p>
      </div>

    </div><!-- /.container -->
<div class="container">
  <?php
echo "Database Status: <label>" . $dbstatus . "</label>";
?>
</div>
<?php CreateNewTeam(team_number, team_name) ?>
<!-- Manage Teams -->
<div class="container"><form action="classes.php" method="post">
Team Number: <input type="text" name="team_number"><br>
Team Name: <input type="text" name="team_name"><br>
<input type="submit">
</form>
</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/scouting/jquery/jquery-3.3.1.min.js"></script>

  </body>
</html>
