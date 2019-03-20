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
include_once '../objects/configuration.php';
 
$database = new Database();
$db = $database->getConnection();
 
$config = new Configuration($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

//var_dump($data);
 
// make sure data is not empty
if( $data != null ){ 
    
    foreach($data->configArr as $configItem) {
 
        // update config item
        //echo "Updating " . $configItem->name . " with " . $configItem->value . "\n"; 
        $config->name  = $configItem->name;
        $config->value = $configItem->value;

        if(!$config->update()){
            // Cannot be updated, probably the option doesn't exist. -> Create it
            $config->create();
            
        }

    }

    // // set response code - 503 service unavailable
    // http_response_code(503);
    
    // // tell the user
    // echo json_encode(array("message" => "Unable to update the config data."));



    // set response code - 201 created
    http_response_code(201);
    
    // tell the user
    echo json_encode(array("message" => "Config data was updated."));

} else{
// tell the user data is incomplete

    // set response code - 400 bad request
    http_response_code(400);
 
    $message = "Unable to update config data, parameter is missing.";

    // tell the user
    echo json_encode(array("message" => $message));
}
?>
