<?php
    require_once('tba_config.php');

    // The Blue Alliance event keys
    // Palmetto        : 2019scmb
    // Smoky Mountains : 2019tnkn
    // SCRIW           : 2019sccol

    // Rocket City Regional : 2019alhu 
    // San Francisco Regional 2019 : 2019casf

    $ch = curl_init("https://www.thebluealliance.com/api/v3/event/2022scan/teams");

    //Set header with the auth key
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-TBA-Auth-Key: ' . $tba_apikey));
    
    // Add headers to the output string
    //curl_setopt($ch, CURLOPT_HEADER, true);

    //return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Exec query
    $curldata = curl_exec($ch);

    // Check for error
    $errorno = curl_errno($ch);
    $errorstring = curl_error($ch);

    // Close handle
    curl_close($ch);

    if ( $errorno == 0 ) {
        //var_dump($curldata);

        //$data = json_decode(file_get_contents("php://stdin"), true);
        $data = json_decode($curldata, true);

        //var_dump($data);
    

        // get database connection
        include_once '../api/config/database.php';

        // instantiate team object
        include_once '../api/objects/team.php';

        $database = new Database();
        $db = $database->getConnection();

        
        foreach($data as $i => $ba_team ) {
            echo "No: " . $i . 
                " ba_key: " . $ba_team["key"] .
                " Teamno: " . $ba_team["team_number"] .
                " Name: " . $ba_team["nickname"] . "\n";
        

        
            $team = new Team($db);
            $team->teamnumber = $ba_team["team_number"];

            if ($team->read()) {
                // Team exist in the db -> update

                $team->ba_team_key = $ba_team["key"];
                $team->name = $ba_team["nickname"];

                if ($team->update()) {
                    echo "Team " . $team->teamnumber . " updated in the DB. \n";
                } else {
                    echo "Team " . $team->teamnumber . " DB update error. \n";
                }

            } else {
                // Team not exist in the db -> create

                $team->ba_team_key = $ba_team["key"];
                $team->name = $ba_team["nickname"];
                $team->team_comments = "";

                if ($team->create()) {
                    echo "Team " . $team->teamnumber . " added to the DB. \n";
                } else {
                    echo "Team " . $team->teamnumber . " adding DB error. \n";
                }

            }

            echo "\n\n";

        }

    } else {
        echo "Error in CURL request.\n" . $errorstring . "\n";
    }

?>
