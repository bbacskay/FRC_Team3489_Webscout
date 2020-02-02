<?php
class Season {
    
    // database connection and table name
    private $conn;
    private $table_name = "seasons";
    
    // object properties
    public $year;
    public $gameTitle;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read season
    function read(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY year ASC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // create season
    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET
            year       = :year,
            game_title = :gametitle";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->year=htmlspecialchars(strip_tags($this->year));
        $this->gameTitle=htmlspecialchars(strip_tags($this->gameTitle));

    
        // bind values
        $stmt->bindParam(":year", $this->year);
        $stmt->bindParam(":gametitle", $this->gameTitle);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }

    // delete event
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE year = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->year=htmlspecialchars(strip_tags($this->year));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->year);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }


    // update event
    function update(){
    
        // query to insert record
        $query = "UPDATE " . $this->table_name . " SET
            game_title=:gametitle
            WHERE year=:year";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->year=htmlspecialchars(strip_tags($this->year));
        $this->gameTitle=htmlspecialchars(strip_tags($this->gameTitle));
    
        // bind values
        $stmt->bindParam(":year", $this->year);
        $stmt->bindParam(":gametitle", $this->gameTitle);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }
}
