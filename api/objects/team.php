<?php
class Team{
    
    // database connection and table name
    private $conn;
    private $table_name = "teams";
    
    // object properties
    public $teamnumber;
    public $name;
    public $team_comments;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read products
    function read(){
        
        // select all query
        $query = "SELECT teamnumber, teamname, team_comments FROM " . $this->table_name . " ORDER BY teamnumber ASC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}
