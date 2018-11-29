<?php 
 include 'includes/DBClass.php';
 
echo 'Creating connection';
$db = new DBClass();
$results = $db->Query('SELECT * FROM teams;');

echo "<table>
<tr>
<th>Team Number</th>
<th>Team Name</th>
<th>Comments</th>
</tr>";
while ($row = mysqli_fetch_array($results)) {
    echo "<tr>";
    echo "<td>" . $row['team_number'] . "</td>";
    echo "<td>" . $row['team_name'] . "</td>";
    echo "<td>" . $row['comments'] . "</td>";
    echo "</tr>";
}
echo "</table>";

?>
 Teams: <?php echo $results ?>
<!-- Manage Teams -->
<div class="container"><form action="admin.php" method="post">
Team Number: <input type="text" name="team_number"><br>
Team Name: <input type="text" name="team_name"><br>
<input type="submit">
</form>

