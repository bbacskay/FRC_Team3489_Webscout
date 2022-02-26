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
$eventId = 16;

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
    4 => "AQ4",
    5 => "AQ5",
    6 => "AQ6",
    7 => "AQ7",
    8 => "AQ8",
    9 => "AQ9",
    10 => "TQ1",
    11 => "TQ2",
    12 => "TQ3",
    13 => "TQ4",
    14 => "TQ5",
    15 => "TQ6",
    16 => "TQ7",
    17 => "TQ8",
    18 => "TQ9",
    19 => "EQ1",
    20 => "EQ2",
    21 => "EQ3",
    22 => "EQ4",
    23 => "EQ5",
    24 => "EQ6",
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
