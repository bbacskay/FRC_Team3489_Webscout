<?php 
 include 'includes/DBClass.php';
 
echo 'Creating connection';
$con = mysqli_connect('localhost', 'scoutingapp', 'team3489', 'scouting');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}else {
  echo "Connection Success!";
}

mysqli_select_db($con, "scouting");
$sql = "SELECT * FROM teams where 1=1";
$results = mysqli_query($con, $sql);

echo "<table>
<tr>
<th>Team Number</th>
<th>Team Name</th>
<th>Comments</th>
<th>Record Count</th>
</tr>";
while ($row = mysqli_fetch_array($results)) {
    echo "<tr>";
    echo "<td>" . $row['team_number'] . "</td>";
    echo "<td>" . $row['team_name'] . "</td>";
    echo "<td>" . $row['comments'] . "</td>";

    echo "</tr>";
}
echo "</table>";
mysqli_close($con);

?>
 Teams: <?php echo $results ?>
<!-- Manage Teams -->
<div class="container"><form action="admin.php" method="post">
Team Number: <input type="text" name="team_number"><br>
Team Name: <input type="text" name="team_name"><br>
<input type="submit">
</form>

