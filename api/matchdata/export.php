<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/csv; charset=UTF-8");
//header("Content-Type: application/json; charset=UTF-8");
//header("Content-Disposition", "attachment;filename=standscouting.csv");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/matchdata.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// !!!!!!!!!!!!!!!!!!!!!!!
// get event id !!!!!!!! TODO!
$eventId = 6;

// Static elements of the csv header
$csvHeaderStdArr = array(
    1 => "Team #",
    2 => "Match #",
    3 => "Scout",
    4 => "Note"
);

// Add the question IDs to the header
$csvHeaderQuestionsArray = array(
    1 => "AQ1",
    2 => "AQ2",
    3 => "AQ3",
    4 => "TQ1",
    5 => "TQ2",
    6 => "TQ3",
    7 => "TQ4",
    8 => "TQ5",
    9 => "EQ1",
    10 => "EQ2"
);

// Create the csv header
$csvHeaderArr = array_merge($csvHeaderStdArr, $csvHeaderQuestionsArray);

//var_dump($csvHeaderArr);
 

// initialize object
$scoutingData = new ScoutData($db);


// query scouting data
$scoutingData->event_id = $eventId;
$stmt = $scoutingData->load();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // scouting data array
    $scoutingDataArr=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        // Add static part
        $scoutingDataItem=array(
            "team_no" => $team_number,
            "match_no" => $match_no,
            "scout_id" => $scout_id,
            "note" => $note
        );

        // Add the questions
        //"data" => json_decode(htmlspecialchars_decode($response)),
        $responses = json_decode(htmlspecialchars_decode($response));

        //var_dump($responses);

        foreach($csvHeaderQuestionsArray as $questionId) {
            //var_dump($questionId);
            if ($responses != null) {
                $key = array_search($questionId, array_column($responses, 'id'));
            } else {
                $key = null;
            }
            //echo "key: " .$key . "\n";
            //var_dump($responses[$key]);
            
            if ($key !== null) {
                //$response='';
                $response = $responses[$key]->response;
                
            } else {
                $response = ' ';
            }
            
            array_push($scoutingDataItem, $response);
        }

        array_push($scoutingDataArr, $scoutingDataItem);
    }

    $output = fopen("php://output",'w') or die("Can't open php://output");
    
    fputcsv($output, $csvHeaderArr, "\t", '"');
    foreach($scoutingDataArr as $matchData) {
        fputcsv($output, $matchData, "\t", '"');
    }

    fclose($output) or die("Can't close php://output");
    } 


?>
