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
$scoutData = new ScoutData($db);


// check parameters exist
if (
    !empty($_GET['matchno']) &&
    !empty($_GET['teamno']) &&
    !empty($_GET['scoutid'])
) {
    // get parameters
    $scoutData->match_id = $_GET['matchno'];
    $scoutData->team_no  = $_GET['teamno'];
    $scoutData->scout_id  = $_GET['scoutid'];

    // query scouting data
    $stmt = $scoutData->read();
    $num = $stmt->rowCount();

    // check if there is a record
    if($num==0){
        // No entry yet, create an empty record
        $scoutData->createEmpty();

        // re-query scouting data
        $stmt = $scoutData->read();
        $num = $stmt->rowCount();

    }

    if($num==1){
        // scouting data (only 1 record is expected!)
        $scoutingDataArr=array();

        // retrieve our table contents
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $scoutingDataArr=array(
            "id" => $scouting_data_id,
            "match_id" => $match_id,
            "team_no" => $team_number,
            "scout_id" => $scout_id,
            "data" => json_decode(htmlspecialchars_decode($response)),
            "note" => $note
        );

        // set response code - 200 OK
        http_response_code(200);

        // show scouting data data in json format
        echo json_encode($scoutingDataArr);
    } else {
        // set response code - 400 bad request
        http_response_code(400);
 
        // tell the user
        echo json_encode(array("message" => "Something went wrong with the record creation or loading."));
    }
} else {
    // tell the user data is incomplete

    // set response code - 400 bad request
    http_response_code(400);

    $message = "Unable to read scouting data, parameter is missing. (";

    if (empty($_GET['matchno'])) {
        $message = $message . " matchno ";  
    }

    if (empty($_GET['teamno'])) {
        $message = $message . " teamno ";  
    }

    if (empty($_GET['scoutid'])) {
        $message = $message . " scoutid ";  
    }

    $message = $message . ")";

    // tell the user
    echo json_encode(array("message" => $message));
}
?>