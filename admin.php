<?php 
 include 'includes/header.php';
 include 'includes/DBClass.php';
 
 $db = new DBClass();
 $results = $db.Query('SELECT * FROM teams;');
 ?>

<!-- Begin Main Content -->

  <body>
  
    <div class="container">

      <div class="starter-template">
        <h1>Administration</h1>
        <p class="lead">This page will be used for configuration.</p>
      </div>

    </div><!-- /.container -->
<div class="container">
 Teams: <?php echo $results ?>
<!-- Manage Teams -->
<div class="container"><form action="admin.php" method="post">
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
