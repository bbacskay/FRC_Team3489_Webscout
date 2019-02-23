<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object file
include_once '../config/database.php';
include_once '../objects/scout.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare match object
$scout = new Scout($db);
 
// get match id
$data = json_decode(file_get_contents("php://input"));
 
// set match id to be deleted
$scout->scout_id = $data->scout_id;
 
// delete the scout
if($scout->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Scout was deleted."));
}
 
// if unable to delete the scout
else {
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete the scout."));
}
?>