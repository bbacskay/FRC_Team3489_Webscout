<?php
// required headers
//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/csv; charset=UTF-8");
//header("Content-Type: application/json; charset=UTF-8");
//header("Content-Disposition", "attachment;filename=standscouting.csv");
?>
<html>
    <head>
        <title>Matchdata export</title>
    </head>
    <body>
        <table>
<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/matchdata.php';


function fputHtmlTableRow(array $dataRowArr) {
    echo "\t\t\t<tr>\n";
    foreach($dataRowArr as $dataCell) {
        echo "\t\t\t\t<td>" . $dataCell . "</td>\n";
    }
    echo "\t\t\t</tr>\n";
}
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();


// Static elements of the csv header
$csvHeaderStdArr = array(
    1 => "Team #",
    2 => "Match #",
    3 => "Scout",
    4 => "Note"
);

// Add the question IDs to the header
$csvHeaderQuestionsArray = array(
    1 => "SQ1",
    2 => "SQ2",
    3 => "SQ3",
    4 => "SQ4",
    5 => "SQ5",
    6 => "SQ6",
    7 => "TQ1",
    8 => "TQ2",
    9 => "TQ3",
    10 => "TQ4",
    11 => "TQ5",
    12 => "EQ1",
    13 => "EQ2"
);

// Create the csv header
$csvHeaderArr = array_merge($csvHeaderStdArr, $csvHeaderQuestionsArray);

//var_dump($csvHeaderArr);
 

// initialize object
$scoutingData = new ScoutData($db);


// query scouting data
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
            "match_id" => $match_id,
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


    fputHtmlTableRow($csvHeaderArr);
    foreach($scoutingDataArr as $matchData) {
        fputHtmlTableRow($matchData);
    }

    
} 


?>
        </table>
    </body>
</html>