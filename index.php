<?php 
  require_once( 'includes/Main.php' );

  $main = new Main();

  $main->pageStart();
  $main->header('Category 5 Scouting');

  $main->navbar('Home');
?>
  
  <!-- Begin Main Content -->
    <div class="container">

      <div class="starter-template">
        <h1><?php echo $_GET["page"] ?></h1>
        <?php include('admin.php') ?>
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-3.3.1.min.js"></script>

  </body>
<?php
  $main->pageEnd();
?>
