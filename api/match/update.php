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
include_once '../objects/match.php';
 
$database = new Database();
$db = $database->getConnection();
 
$match = new Match($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->matchid) &&
    !empty($data->blue_1) &&
    !empty($data->blue_2) &&
    !empty($data->blue_3) &&
    !empty($data->red_1) &&
    !empty($data->red_2) &&
    !empty($data->red_3)
){ 
    // set match property values
    $match->match_id = $data->matchid;
    $match->blue_1 = $data->blue_1;
    $match->blue_2 = $data->blue_2;
    $match->blue_3 = $data->blue_3;
    $match->red_1 = $data->red_1;
    $match->red_2 = $data->red_2;
    $match->red_3 = $data->red_3;
 
    // create the match
    if($match->update()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "match was updated."));
    }
 
    // if unable to update the match, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to update the match."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update the match. Data is incomplete."));
}
?>