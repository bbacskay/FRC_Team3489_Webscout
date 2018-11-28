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
        <h1>Home Page</h1>
        <p class="lead">This is the home page.</p>
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
