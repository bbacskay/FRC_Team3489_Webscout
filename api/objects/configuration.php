<?php
class Configuration {
    
    // database connection and table name
    private $conn;
    private $table_name = "config";
    
    // object properties
    public $name;   // option name
    public $value;  // option value
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read team
    function load(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY name ASC";
        
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
            name  = :optionName,
            value = :optionName";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->value=htmlspecialchars(strip_tags($this->value));
        
    
        // bind values
        $stmt->bindParam(":optionName", $this->name);
        $stmt->bindParam(":optionName", $this->value);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }

    // Update an option's value
    function update() {
        
        // update query
        $query = "UPDATE " . $this->table_name . "SET value=:optionValue WHERE name=:optionName";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->value=htmlspecialchars(strip_tags($this->value));

        // bind values
        $stmt->bindParam(":optionName", $this->name);
        $stmt->bindParam(":optionName", $this->value);

        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;

    }

    // delete event
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE name = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->name);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
}
