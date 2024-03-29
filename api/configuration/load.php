<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/configuration.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$configuration = new Configuration($db);
 

// query matches
$stmt = $configuration->load();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // products array
    $config_arr=array();
    //$team_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        

        $config_item=array(
            "name" => $row['name'],
            "value" => $row['value']
        );

        array_push($config_arr, $config_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($config_arr);
}
else {
    // no match found

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no events found
    echo json_encode(
        array("message" => "No events found.")
    );
}

 