<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/matchdata.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$scoutingData = new ScoutData($db);

// check parameters exist
if (!empty($_GET['teamno'])) {
    // get parameters
    $scoutingData->team_no = $_GET['teamno'];

    // query notes
    $stmt = $scoutingData->comments();
    $num = $stmt->rowCount();

    // scouting data array
    $scoutingDataArr=array();

    // check if more than 0 record found
    if($num>0){

        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);

            $noteItem=array(
                "matchNo" => $match_id,
                "note" => $note
            );
    

            array_push($scoutingDataArr, $noteItem);
        }

        
    } 

    // set response code - 200 OK
    http_response_code(200);

    // show comments in json format
    echo json_encode($scoutingDataArr);

} else {
    // set response code - 400 bad request
    http_response_code(400);
 
    $message = "Unable to read comments, parameter is missing. (";

    if (empty($data->data)) {
        $message = $message . " teamno ";  
    }

    $message = $message . ")";

    // tell the user
    echo json_encode(array("message" => $message));
}


?>