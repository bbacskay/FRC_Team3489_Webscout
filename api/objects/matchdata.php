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
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // load (all) match data
    function load(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY match_id,team_number ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        //$tmpEventId = htmlspecialchars(strip_tags($eventId));
        //$tmpCompLevel = htmlspecialchars(strip_tags($compLevel));

        // bind values
        //$stmt->bindValue(":compLevel", $compLevel, PDO::PARAM_INT);
        //$stmt->bindValue(":eventId", $tmpEventId, PDO::PARAM_STR);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // read match data for a team
    // Required properties match_id, team_no
    function read(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE match_id=:match_id AND team_number=:team_no";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $tmpMatchId = htmlspecialchars(strip_tags($this->match_id));
        $tmpTeamNo = htmlspecialchars(strip_tags($this->team_no));

        // bind values
        $stmt->bindValue(":match_id", $tmpMatchId, PDO::PARAM_INT);
        $stmt->bindValue(":team_no", $tmpTeamNo, PDO::PARAM_STR);
        
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
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
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
        $this->data=htmlspecialchars(strip_tags($this->data));
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
    
}
