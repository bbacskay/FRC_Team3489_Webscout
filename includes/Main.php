<?php
// This is the Main class for the scouting app

    class Main {
        // Properties


        // Methods
        public function pageStart() {
            echo "<!DOCTYPE html>\n";
            echo "<html lang=\"en\">\n";   
        }

        public function pageEnd() {
            echo "</html>";
        }

        public function header(string $title) {
            echo "  <head>\n";
            echo "    <meta charset=\"utf-8\">\n";
            echo "    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
            echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
            echo "    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->\n";
            echo "    <meta name=\"description\" content=\"\">\n";
            echo "    <meta name=\"author\" content=\"Team 3489\">\n";
            echo "    <link rel=\"icon\" href=\"../../favicon.ico\">\n\n";
            echo "    <title>" . $title . "</title>\n\n";
            echo "    <!-- Bootstrap core CSS -->\n";
            echo "    <link href=\"css/bootstrap.min.css\" type=\"text/css\" rel=\"stylesheet\">\n\n";
            echo "    <!-- Cat5 CSS -->\n";    
            echo "    <link href=\"css/scouting.css\" type=\"text/css\" rel=\"stylesheet\">\n";
            echo "  </head>\n";
        }

        public function navbar(string $title){
           echo "<body>\n";
           echo "<nav class=\"navbar navbar-inverse\">\n";
           echo "<div class=\"container\">\n";
           echo "<div id=\"navbar\" class=\"collapse navbar-collapse\">\n";
           echo "<ul class=\"nav navbar-nav\">\n";
           echo "<li class=\"active\"><a href=\"index.php\">Home</a></li>\n";
           echo "<li><a href=\"datainput.php\">Scout a match</a></li>\n";
           echo "<li><a href=\"dataview.php\">View Data</a></li>\n";
           echo "<li><a href=\"admin.php\">Admin</a></li>\n";
           echo "</ul>\n";
           echo "</div><!--/.nav-collapse -->\n";
           echo "</div>\n";
           echo "</nav>\n";
        }
    }
?>