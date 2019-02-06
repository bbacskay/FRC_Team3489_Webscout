<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/team.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$team = new Team($db);
 
// query products
$stmt = $team->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $team_arr=array();
    //$team_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $team_item=array(
            "number" => $team_number,
            "ba_team_key" => $ba_team_key,
            "name" => $teamname,
            "comment" => html_entity_decode($team_comments)
        );
 
        array_push($team_arr, $team_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($team_arr);
}
else{
    // no team found

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no teams found
    echo json_encode(
        array("message" => "No products found.")
    );
}
