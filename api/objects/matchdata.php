<?php
class ScoutData {
    
    // database connection and table name
    private $conn;
    private $table_name = "scouting_data";
    
    // object properties
    public $id;
    public $match_id;
    public $team_no;
    public $scout_id;
    public $data;
    public $note;

    public $event_id;
    public $comp_event;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // load (all) match data
    function load(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . 
                " LEFT JOIN matches on " . $this->table_name . ".match_id=matches.match_id" .
                " WHERE event_id=:eventId" .
                " ORDER BY matches.match_no,team_number ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $tmpEventId = htmlspecialchars(strip_tags($this->event_id));
        //$tmpCompLevel = htmlspecialchars(strip_tags($compLevel));

        // bind values
        //$stmt->bindValue(":compLevel", $compLevel, PDO::PARAM_INT);
        $stmt->bindValue(":eventId", $tmpEventId, PDO::PARAM_INT);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // read match data for a team
    // Required properties match_id, team_no
    function read(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " LEFT JOIN scouts ON scouting_data.scout_id=scouts.scout_id" . 
        " WHERE match_id=:match_id AND team_number=:team_no";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $tmpMatchId = htmlspecialchars(strip_tags($this->match_id));
        $tmpTeamNo = htmlspecialchars(strip_tags($this->team_no));

        // bind values
        $stmt->bindValue(":match_id", $tmpMatchId, PDO::PARAM_INT);
        $stmt->bindValue(":team_no", $tmpTeamNo, PDO::PARAM_INT);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
}

    // create new empty record
    // required properties:
    // match_id, team_number, scout_id
    function createEmpty(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET
            match_id=:match_id,
            team_number=:team_no,
            scout_id=:scout_id,
            response='',
            note=''";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->match_id=htmlspecialchars(strip_tags($this->match_id));
        $this->team_no=htmlspecialchars(strip_tags($this->team_no));
        $this->scout_id=htmlspecialchars(strip_tags($this->scout_id));
    
        // bind values
        $stmt->bindParam(":match_id", $this->match_id);
        $stmt->bindParam(":team_no", $this->team_no);
        $stmt->bindParam(":scout_id", $this->scout_id);
    
        //var_dump($stmt);
        //echo "match_id=" . $this->match_id . "\n";
        //echo "team_no=" . $this->team_no . "\n";
        //echo "scout_id=" . $this->scout_id . "\n";

        // execute query
        if($stmt->execute()){
            //echo "true\n";
            return true;
        }
    
        echo "false\n";
        //var_dump($stmt->errorInfo());
        return false;        
    }

    // delete match data entry
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE scouting_data_id = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // update scouting data
    // required properties:
    // scouting_data_id, response, note
    function update(){
    
        // query to insert record
        $query = "UPDATE " . $this->table_name . " SET
            response=:data,
            note=:note
            WHERE scouting_data_id=:id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        //$this->data=htmlspecialchars(strip_tags($this->data));
        $this->data=strip_tags($this->data);
        $this->note=htmlspecialchars(strip_tags($this->note));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind values
        $stmt->bindParam(":data", $this->data);
        $stmt->bindParam(":note", $this->note);
        $stmt->bindParam(":id", $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }


    // load previous comments
    function comments(){
    
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE note <>'' AND team_number=:teamno ORDER BY match_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $tmpTeamNo = htmlspecialchars(strip_tags($this->team_no));
        //$tmpCompLevel = htmlspecialchars(strip_tags($compLevel));

        // bind values
        $stmt->bindValue(":teamno", $tmpTeamNo, PDO::PARAM_INT);
        //$stmt->bindValue(":eventId", $tmpEventId, PDO::PARAM_STR);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    

    // load match monitoring data
    function monitor(){
        $monitoringData = array();
        
        // select all query
        $query = "SELECT * FROM matches WHERE event_id=:eventId AND comp_level=:compLevel ORDER BY match_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $tmpEventId = htmlspecialchars(strip_tags($this->event_id));
        $tmpCompLevel = htmlspecialchars(strip_tags($this->comp_level));

        // bind values
        $stmt->bindValue(":compLevel", $tmpCompLevel, PDO::PARAM_STR);
        $stmt->bindValue(":eventId", $tmpEventId, PDO::PARAM_INT);
        
        $matchDataArr=Array();

        // execute query
        $stmt->execute();
        $num=$stmt->rowCount();



        if ($num>0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
               // echo('             ').
                extract($row);
        
                $matchDataItem=array(
                    "match_id" => $match_id,
                    "match_no" => $match_no,
                    "ba_match_key" => $ba_match_key,
                    "blue_1" => $blue_1,
                    "blue_2" => $blue_2,
                    "blue_3" => $blue_3,
                    "red_1" => $red_1,
                    "red_2" => $red_2,
                    "red_3" => $red_3
                );
        
                array_push($matchDataArr, $matchDataItem);
                //var_dump($matchDataArr);
            }

            //echo "matchDataArr:\n";
            //var_dump($matchDataArr);

            // Get scouting data for the team
            foreach($matchDataArr as $MatchData) {
                $blue1Ok = false;
                $blue2Ok = false;
                $blue3Ok = false;

                $red1Ok = false;
                $red2Ok = false;
                $red3Ok = false;

                $blue_1 = new stdClass();
                $blue_2 = new stdClass();
                $blue_3 = new stdClass();
                $red_1 = new stdClass();
                $red_2 = new stdClass();
                $red_3 = new stdClass();
                //var_dump($MatchData);
                $this->match_id=$MatchData['match_id'];

                $monitoringDataItem = array();

                // Get Blue 1 team scouting data
                $this->team_no=$MatchData['blue_1'];
                $stmt2 = $this->read();
                $num2 = $stmt2->rowCount();

                //echo "match_id=" . $this->match_id . " blue 1 team_no=" . $this->team_no . "\n"; 
                //var_dump($stmt2);
                //echo "Num2:" . $num2 . "\n";
                
                $blue_1->teamNo = $MatchData['blue_1'];  // Add team number anyway

                if ($num2>0) {
                    $blue1Ok = true;

                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);

                    //echo "row\n";
                    //var_dump($row);


                    $blue_1->scouting_data_id = $row['scouting_data_id'];
                    $blue_1->scout = $row['firstname'] . " " . $row['lastname'];
                    $blue_1->note = $row['note'];

                    $responses=json_decode(htmlspecialchars_decode($row['response']));
                    //var_dump($responses);

                    $questions = array();
                    
                    if (!empty($responses)) {
                        foreach($responses as $response) {
                            $question=new stdClass();
                            $question->id=$response->id;
                            $question->value=$response->response;
                            $question->ok=isset($response->response);

                            array_push($questions,$question);
                        }
                    }
                    
                    $blue_1->questions=$questions;

                    //var_dump($blue_1);

                } else {
                    $blue1Ok = false;
                }

                $monitoringDataItem['blue_1']=$blue_1;
                

                // Get Blue 2 team scouting data
                $this->team_no=$MatchData['blue_2'];
                $stmt2 = $this->read();
                $num2 = $stmt2->rowCount();

                //echo "match_id=" . $this->match_id . " blue 2 team_no=" . $this->team_no . "\n"; 
                //var_dump($stmt2);
                //echo "Num2:" . $num2 . "\n";

                $blue_2->teamNo = $MatchData['blue_2'];  // Add team number anyway

                if ($num2>0) {
                    $blue2Ok = true;

                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);

                    $blue_2->scouting_data_id = $row['scouting_data_id'];
                    $blue_2->scout = $row['firstname'] . " " . $row['lastname'];
                    $blue_2->note = $row['note'];

                    $responses=json_decode(htmlspecialchars_decode($row['response']));
                    //var_dump($responses);

                    $questions = array();
                    
                    if (!empty($responses)) {
                        foreach($responses as $response) {
                            $question=new stdClass();
                            $question->id=$response->id;
                            $question->value=$response->response;
                            $question->ok=isset($response->response);

                            array_push($questions,$question);
                        }
                    }

                    $blue_2->questions=$questions;

                    //var_dump($blue_2);

                } else {
                    $blue2Ok = false;
                }

                $monitoringDataItem['blue_2']=$blue_2;

                // Get Blue 3 team scouting data
                $this->team_no=$MatchData['blue_3'];
                $stmt2 = $this->read();
                $num2 = $stmt2->rowCount();

                //echo "match_id=" . $this->match_id . " blue 3 team_no=" . $this->team_no . "\n"; 
                //var_dump($stmt2);
                //echo "Num2:" . $num2 . "\n";

                $blue_3->teamNo = $MatchData['blue_3'];  // Add team number anyway

                if ($num2>0) {
                    $blue3Ok = true;

                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);

                    $blue_3->scouting_data_id = $row['scouting_data_id'];
                    $blue_3->scout = $row['firstname'] . " " . $row['lastname'];
                    $blue_3->note = $row['note'];

                    $responses=json_decode(htmlspecialchars_decode($row['response']));
                    //var_dump($responses);

                    $questions = array();
                    
                    foreach($responses as $response) {
                        $question=new stdClass();
                        $question->id=$response->id;
                        $question->value=$response->response;
                        $question->ok=isset($response->response);

                        array_push($questions,$question);
                    }
                    
                    $blue_3->questions=$questions;

                    //var_dump($blue_3);

                } else {
                    $blue3Ok = false;
                }

                $monitoringDataItem['blue_3']=$blue_3;


                // Get Red 1 team scouting data
                $this->team_no=$MatchData['red_1'];
                $stmt2 = $this->read();
                $num2 = $stmt2->rowCount();

                //echo "match_id=" . $this->match_id . " red 1 team_no=" . $this->team_no . "\n"; 
                //var_dump($stmt2);
                //echo "Num2:" . $num2 . "\n";

                $red_1->teamNo = $MatchData['red_1'];  // Add team number anyway

                if ($num2>0) {
                    $red1Ok = true;

                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);

                    $red_1->scouting_data_id = $row['scouting_data_id'];
                    $red_1->scout = $row['firstname'] . " " . $row['lastname'];
                    $red_1->note = $row['note'];

                    $responses=json_decode(htmlspecialchars_decode($row['response']));
                    //var_dump($responses);

                    $questions = array();
                    
                    foreach($responses as $response) {
                        $question=new stdClass();
                        $question->id=$response->id;
                        $question->value=$response->response;
                        $question->ok=isset($response->response);

                        array_push($questions,$question);
                    }
                    
                    $red_1->questions=$questions;

                    //var_dump($red_1);

                } else {
                    $red1Ok = false;
                }

                $monitoringDataItem['red_1']=$red_1;
                

                if ( ($blue1Ok == true) ||
                     ($blue2Ok == true) ||
                     ($blue3Ok == true) ||
                     ($red1Ok == true) ||
                     ($red2Ok == true) ||
                     ($red3Ok == true) )
                {
                    $monitoringDataItem['match_no']=$MatchData['match_no'];
                    $monitoringDataItem['ba_match_key']=$MatchData['ba_match_key'];

                    array_push($monitoringData,$monitoringDataItem);
                }
            }

        }

        //echo "MonitoringData\n";
        //var_dump($monitoringData);
        return $monitoringData;
    }
}
