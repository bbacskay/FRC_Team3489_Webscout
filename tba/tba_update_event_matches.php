<?php
    require_once('tba_config.php');

    $event_id = 1;  // Palmetto Regional 2019

    $ch = curl_init("https://www.thebluealliance.com/api/v3/event/2019scmb/matches");

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

    function getTeamFromBaTeamkey($ba_team_key)
    {
        $retval = substr($ba_team_key, 3, strlen($ba_team_key)-3);  //;
        return $retval;
    }


    if ( $errorno == 0 ) {
        //var_dump($curldata);

        //$data = json_decode(file_get_contents("php://stdin"), true);
        $data = json_decode($curldata, true);

        //var_dump($data);
    

        // get database connection
        include_once '../api/config/database.php';

        // include match object
        include_once '../api/objects/match.php';

        $database = new Database();
        $db = $database->getConnection();

        echo "\n";

        foreach($data as $i => $ba_match ) {
            echo "Index: " . $i . 
                " ba_match_key: " . $ba_match["key"] .
                " Match no: " . $ba_match["match_number"] .
                " comp level: " . $ba_match["comp_level"] .
                "\n";
            echo "Alliances:\n";
            echo "Blue 1: " . getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][0]) . " (" . $ba_match['alliances']['blue']['team_keys'][0] . ") " .
                 "Blue 2: " . getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][1]) . " (" . $ba_match['alliances']['blue']['team_keys'][1] . ") " .
                 "Blue 3: " . getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][2]) . " (" . $ba_match['alliances']['blue']['team_keys'][2] . ") " .
                 "\n";
            echo "Red 1: " . getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][0]) . " (" . $ba_match['alliances']['red']['team_keys'][0] . ") " .
                 "Red 2: " . getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][1]) . " (" . $ba_match['alliances']['red']['team_keys'][1] . ") " .
                 "Red 3: " . getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][2]) . " (" . $ba_match['alliances']['red']['team_keys'][2] . ") " .
                 "\n";
            //var_dump($ba_match['alliances']['red']);
        

        
            $match = new Match($db);
            $match->event_id = $event_id;
            $match->matchno = $ba_match["match_number"];
            $match->comp_level = $ba_match["comp_level"];

            if ($match->read()) {
                // Match exist in the db -> update

                $match->ba_match_no = $ba_match["key"];

                $match->blue_1 = getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][0]);
                $match->blue_2 = getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][1]);
                $match->blue_3 = getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][2]);

                $match->red_1 = getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][0]);
                $match->red_2 = getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][1]);
                $match->red_3 = getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][2]);
                

                if ($match->update()) {
                    echo "(" . $match->match_id . ") Match " . $match->matchno . " in event " . $match->event_id . " was updated in the DB. \n";
                } else {
                    echo "(" . $match->match_id . ") Match " . $match->matchno . " in event " . $match->event_id . " DB update error. \n";
                }

            } else {
                // Match not exist in the db -> create

                $match->ba_match_no = $ba_match["key"];

                $match->blue_1 = getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][0]);
                $match->blue_2 = getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][1]);
                $match->blue_3 = getTeamFromBaTeamkey($ba_match['alliances']['blue']['team_keys'][2]);

                $match->red_1 = getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][0]);
                $match->red_2 = getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][1]);
                $match->red_3 = getTeamFromBaTeamkey($ba_match['alliances']['red']['team_keys'][2]);

                //var_dump($match);

                if ($match->create()) {
                    echo "Match " . $match->matchno . " in event " . $match->event_id . " added to the DB. \n";
                } else {
                    echo "Match " . $match->matchno . " in event " . $match->event_id . " adding DB error. \n";
                }

            }

            echo "\n\n";

        }

    } else {
        echo "Error in CURL request.\n" . $errorstring . "\n";
    }

?>
