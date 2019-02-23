<?php
class Scout {
    
    // database connection and table name
    private $conn;
    private $table_name = "scouts";
    
    // object properties
    public $scout_id;
    public $loginname;
    public $password;
    public $firstname;
    public $lastname;
    public $mentor;

    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read scouts
    function read(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY loginname ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // create update
    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET
            loginname=:login,
            firstname=:firstname,
            lastname=:lastname,
            password=:password,
            mentor=:mentor";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->loginname=htmlspecialchars(strip_tags($this->loginname));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->mentor=htmlspecialchars(strip_tags($this->mentor));
    
        // bind values
        $stmt->bindParam(":login", $this->loginname);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":mentor", $this->mentor);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }

    // delete match
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE scout_id = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->scout_id=htmlspecialchars(strip_tags($this->scout_id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->scout_id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // update scout
    function update(){
    
        // query to insert record
        $query = "UPDATE " . $this->table_name . " SET
            loginname=:loginname,
            firstname=:firstname,
            lastname=:lastname,
            mentor=:mentor,
            password=:password 
            WHERE scout_id=:scout_id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->scout_id=htmlspecialchars(strip_tags($this->scout_id));
        $this->loginname=htmlspecialchars(strip_tags($this->loginname));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->mentor=htmlspecialchars(strip_tags($this->mentor));
        $this->password=htmlspecialchars(strip_tags($this->password));
    
        // bind values
        $stmt->bindParam(":scout_id", $this->scout_id);
        $stmt->bindParam(":loginname", $this->loginname);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":mentor", $this->mentor);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }


}
