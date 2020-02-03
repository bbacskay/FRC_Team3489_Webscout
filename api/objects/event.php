<?php
class Event {
    
    // database connection and table name
    private $conn;
    private $table_name = "events";
    
    // object properties
    public $event_id;
    public $year;
    public $ba_event_key;
    public $name;
    public $location;
    public $dateStart;
    public $dateEnd;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read seasons
    function read(){
        
        if ( empty($this->year) ) {
            // select all query
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY event_id ASC";
        } else {
            // select only from the season query
            $query = "SELECT * FROM " . $this->table_name . " WHERE year=:year ORDER BY event_id ASC";
        }
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        if ( !empty($this->year) ) {
            // sanitize
            $this->year=htmlspecialchars(strip_tags($this->year));

            // bind values
            $stmt->bindParam(":year", $this->year);
        }

        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // create event
    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET
            year         = :year,
            ba_event_key = :baEventKey,
            name         = :name,
            location     = :location,
            datestart    = :datestart,
            dateend      = :dateend";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->year=htmlspecialchars(strip_tags($this->year));
        $this->ba_event_key=htmlspecialchars(strip_tags($this->ba_event_key));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->dateStart=htmlspecialchars(strip_tags($this->dateStart));
        $this->dateEnd=htmlspecialchars(strip_tags($this->dateEnd));
    
        // bind values
        $stmt->bindParam(":year", $this->year);
        $stmt->bindParam(":baEventKey", $this->ba_event_key);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":datestart", $this->dateStart);
        $stmt->bindParam(":dateend", $this->dateEnd);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }

    // delete event
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE event_id = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->event_id=htmlspecialchars(strip_tags($this->event_id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->event_id);
    
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
            year=:year,
            ba_event_key=:baeventkey,
            name=:name,
            location=:location,
            datestart=:datestart,
            dateend=:dateend 
            WHERE event_id=:event_id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->event_id=htmlspecialchars(strip_tags($this->event_id));
        $this->year=htmlspecialchars(strip_tags($this->year));
        $this->ba_event_key=htmlspecialchars(strip_tags($this->ba_event_key));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->dateStart=htmlspecialchars(strip_tags($this->dateStart));
        $this->dateEnd=htmlspecialchars(strip_tags($this->dateEnd));
    
        // bind values
        $stmt->bindParam(":event_id", $this->event_id);
        $stmt->bindParam(":year", $this->year);
        $stmt->bindParam(":baeventkey", $this->ba_event_key);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":datestart", $this->dateStart);
        $stmt->bindParam(":dateend", $this->dateEnd);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }
}
