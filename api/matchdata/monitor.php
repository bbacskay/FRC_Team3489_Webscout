<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/matchdata.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$scoutingData = new ScoutData($db);

// Get parameters based on how the script was called
if (defined('STDIN')) {
    // Script was called from command line

    echo "Called from command line\n";

    if (!empty($argv[1])) {
        $eventId = $argv[1];
    }

    if (!empty($argv[2])) {
        $compLevel = $argv[2];
    } else {
        $compLevel = 'qm';
    }

} else {
    // Script was called through the web server
    if (!empty($_GET['eventid'])) {
        $eventId = $_GET['eventid'];
    } else {
        $eventId = NULL;
    }
    
    if (!empty($_GET['complevel'])) {
        $compLevel = $_GET['complevel'];
    } else {
        $compLevel = 'qm';
    }
}

if (defined('STDIN')) {
    echo "eventId:" . $eventId . "\n";
    echo "compLevel:" . $compLevel . "\n";
}

// make sure data is not empty
if (!empty($eventId) &&
    !empty($compLevel)) {

    // query monitoring data
    $scoutingData->event_id=$eventId;
    $scoutingData->comp_level=$compLevel;

    $monitoringData = $scoutingData->monitor();
    $num = count($monitoringData);

    //var_dump($monitoringData);

    // check if more than 0 record found
    if($num>0){

        // scouting data array
        $monitoringDataArr=array();

        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        ////foreach($monitoringData as $matchData){
	for($i=0;$i<10;$i++){
	    $matchData=$monitoringData[$i];
            $monitoringDataItem=array(
                "match_no" => $matchData['match_no'],
                "ba_match_key" => $matchData['ba_match_key'],
                "blue_1" => $matchData['blue_1'],
                "blue_2" => $matchData['blue_2'],
                "blue_3" => $matchData['blue_3'],
                "red_1"  => $matchData['red_1'],
                "red_2"  => $matchData['red_2'],
                "red_3"  => $matchData['red_3']
            );

            /*
            "team_no" => $team_number,
                "scout_id" => $scout_id,
                "data" => json_decode(htmlspecialchars_decode($response)),
                "note" => $note
            */



            array_push($monitoringDataArr, $monitoringDataItem);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show products data in json format
        echo json_encode($monitoringDataArr);
    } else {
        // no scout found

        // set response code - 404 Not found
        http_response_code(404);

        // tell the user no scouting data found
        echo json_encode(
            array("message" => "No scouting data found.")
        );
    }

} else { // tell the user data is incomplete

    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Missing event ID or compLevel"));

} 
