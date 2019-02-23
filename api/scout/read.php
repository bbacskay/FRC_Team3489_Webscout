<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/scout.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$scout = new Scout($db);


// query scouts
$stmt = $scout->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // scout array
    $scout_arr=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $scout_item=array(
            "scout_id" => $scout_id,
            "loginname" => $loginname,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "password" => $password,
            "mentor" => ($mentor == "1")
        );

        array_push($scout_arr, $scout_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($scout_arr);
} else {
    // no scout found

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no scout found
    echo json_encode(
        array("message" => "No scout found.")
    );
}
