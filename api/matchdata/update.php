<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
 
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/matchdata.php';
 
$database = new Database();
$db = $database->getConnection();
 
$scoutingData = new ScoutData($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->id) &&
    !empty($data->scoutid) &&
    !empty($data->data) &&
    isset($data->note)
){ 
    // set scouting data property values
    $scoutingData->id   = $data->id;
    $scoutingData->scout_id = $data->scoutid;
    $scoutingData->data = json_encode($data->data);
    $scoutingData->note = $data->note;
 
    // update the scouting data
    if($scoutingData->update()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "scouting data was updated."));
    }
 
    // if unable to update the scout, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to update the scouting data."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    $message = "Unable to update scouting data, parameter is missing. (";

    if (empty($data->id)) {
        $message = $message . " id ";  
    }

    if (empty($data->data)) {
        $message = $message . " data ";  
    }

    if (empty($data->scoutid)) {
        $message = $message . " scoutid ";  
    }

    $message = $message . ")";

    // tell the user
    echo json_encode(array("message" => $message));
}
?>
