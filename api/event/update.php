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
include_once '../objects/event.php';
 
$database = new Database();
$db = $database->getConnection();
 
$event = new Event($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->id) &&
    !empty($data->baEventKey) &&
    !empty($data->name) &&
    !empty($data->location) &&
    !empty($data->startDate) &&
    !empty($data->endDate)
){ 
    // set scout property values
    $event->event_id     = $data->id;
    $event->ba_event_key = $data->baEventKey;
    $event->name         = $data->name;
    $event->location     = $data->location;
    $event->dateStart    = $data->startDate;
    $event->dateEnd      = $data->endDate;

 
    // update the event
    if($event->update()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "scout was updated."));
    }
 
    // if unable to update the scout, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to update the event."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update the event. Data is incomplete."));
}
?>