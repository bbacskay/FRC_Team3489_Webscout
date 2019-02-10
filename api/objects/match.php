<?php
class Match {
    
    // database connection and table name
    private $conn;
    private $table_name = "matches";
    
    // object properties
    public $match_id;
    public $event_id;
    public $comp_level;    // qm,ef,qf,sf,f
    public $matchno;
    public $blue_1;
    public $blue_2;
    public $blue_3;
    public $red_1;
    public $red_2;
    public $red_3;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read match
    function read(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE comp_level='qm' ORDER BY match_no ASC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // create event
    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET
            event_id=:eventid,
            comp_level=:complevel,
            match_no=:matchno,
            blue_1=:blue1,
            blue_2=:blue2,
            blue_3=:blue3,
            red_1=:red1,
            red_2=:red2,
            red_3=:red3";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->event_id=htmlspecialchars(strip_tags($this->event_id));
        $this->comp_level=htmlspecialchars(strip_tags($this->comp_level));
        $this->matchno=htmlspecialchars(strip_tags($this->matchno));
        $this->blue_1=htmlspecialchars(strip_tags($this->blue_1));
        $this->blue_2=htmlspecialchars(strip_tags($this->blue_2));
        $this->blue_3=htmlspecialchars(strip_tags($this->blue_3));
        $this->red_1=htmlspecialchars(strip_tags($this->red_1));
        $this->red_2=htmlspecialchars(strip_tags($this->red_2));
        $this->red_3=htmlspecialchars(strip_tags($this->red_3));
    
        // bind values
        $stmt->bindParam(":eventid", $this->event_id);
        $stmt->bindParam(":complevel", $this->comp_level);
        $stmt->bindParam(":matchno", $this->matchno);
        $stmt->bindParam(":blue1", $this->blue_1);
        $stmt->bindParam(":blue2", $this->blue_2);
        $stmt->bindParam(":blue3", $this->blue_3);
        $stmt->bindParam(":red1", $this->red_1);
        $stmt->bindParam(":red2", $this->red_2);
        $stmt->bindParam(":red3", $this->red_3);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }

    // delete match
    function delete(){

    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE match_id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->match_id=htmlspecialchars(strip_tags($this->match_id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->match_id);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
    }
}
