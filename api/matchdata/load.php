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


// query scouting data
$stmt = $scoutingData->load();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // scouting data array
    $scoutingDataArr=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $scoutingDataItem=array(
            "id" => $scouting_data_id,
            "match_id" => $match_id,
            "team_no" => $team_number,
            "scout_id" => $scout_id,
            "data" => json_decode(htmlspecialchars_decode($response)),
            "note" => $note
        );

        array_push($scoutingDataArr, $scoutingDataItem);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($scoutingDataArr);
} else {
    // no scout found

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no scouting data found
    echo json_encode(
        array("message" => "No scouting data found.")
    );
}
