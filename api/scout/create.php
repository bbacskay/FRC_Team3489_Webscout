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
include_once '../objects/scout.php';
 
$database = new Database();
$db = $database->getConnection();
 
$scout = new Scout($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(
    !empty($data->loginname) &&
    !empty($data->firstname) &&
    !empty($data->lastname) &&
    !empty($data->password)
){ 
    // set match property values
    $scout->loginname = $data->loginname;
    $scout->firstname = $data->firstname;
    $scout->lastname = $data->lastname;
    $scout->password = $data->password;
    if($data->mentor == NULL) {
        $scout->mentor = false;
    } else {
        $scout->mentor = $data->mentor;
    }
 

    // create the scout
    if($scout->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "scout was created."));
    }
 
    // if unable to create the match, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create scout."));
    }

}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create scout. Data is incomplete."));
}
?>