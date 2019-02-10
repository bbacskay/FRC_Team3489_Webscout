<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/match.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$match = new Match($db);
 
// query products
$stmt = $match->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $match_arr=array();
    //$team_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $match_item=array(
            "matchNo" => $match_no,
            "blue1TeamNumber" => $blue_1,
            "blue2TeamNumber" => $blue_2,
            "blue3TeamNumber" => $blue_3,
            "red1TeamNumber" => $red_1,
            "red2TeamNumber" => $red_2,
            "red3TeamNumber" => $red_3
        );
 
        array_push($match_arr, $match_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($match_arr);
}
else {
    // no team found

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no teams found
    echo json_encode(
        array("message" => "No products found.")
    );
}
