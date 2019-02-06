<?php
class Team {
    
    // database connection and table name
    private $conn;
    private $table_name = "teams";
    
    // object properties
    public $teamnumber;
    public $ba_team_key;
    public $name;
    public $team_comments;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read team
    function read(){
        
        // select all query
        $query = "SELECT team_number, ba_team_key, teamname, team_comments FROM " . $this->table_name . " ORDER BY team_number ASC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // create team
    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET
            team_number=:teamno,
            ba_team_key=:ba_team_key,
            teamname=:name,
            team_comments=:comment";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->teamnumber=htmlspecialchars(strip_tags($this->teamnumber));
        $this->ba_team_key=htmlspecialchars(strip_tags($this->ba_team_key));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->team_comments=htmlspecialchars(strip_tags($this->team_comments));
    
        // bind values
        $stmt->bindParam(":teamno", $this->teamnumber);
        $stmt->bindParam(":ba_team_key", $this->ba_team_key);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":comment", $this->team_comments);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }

    // delete team
    function delete(){

    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE teamnumber = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->teamnumber=htmlspecialchars(strip_tags($this->teamnumber));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->teamnumber);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
    }
}
