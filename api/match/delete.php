<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object file
include_once '../config/database.php';
include_once '../objects/match.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare match object
$match = new Match($db);
 
// get match id
$data = json_decode(file_get_contents("php://input"));
 
// set match id to be deleted
$match->match_id = $data->match_id;
 
// delete the team
if($match->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Match was deleted."));
}
 
// if unable to delete the match
else {
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete the match."));
}
?>