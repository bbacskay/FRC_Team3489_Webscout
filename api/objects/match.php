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
    public $ba_match_no;   // The Blue Alliances match code
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
    
    // load match list
    function load(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE comp_level=:compLevel and event_id=:eventId ORDER BY match_no ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $this->event_id = htmlspecialchars(strip_tags($this->event_id));
        $this->comp_level = htmlspecialchars(strip_tags($this->comp_level));

        // bind values
        $stmt->bindValue(":compLevel", $this->comp_level, PDO::PARAM_STR);
        $stmt->bindValue(":eventId", $this->event_id, PDO::PARAM_INT);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // read match
    function read(){
        
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE comp_level=:compLevel AND event_id=:eventId AND match_no=:matchNo";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $this->event_id = htmlspecialchars(strip_tags($this->event_id));
        $this->comp_level = htmlspecialchars(strip_tags($this->comp_level));
        $this->matchno = htmlspecialchars(strip_tags($this->matchno));

        // bind values
        $stmt->bindValue(":compLevel", $this->comp_level, PDO::PARAM_STR);
        $stmt->bindValue(":eventId", $this->event_id, PDO::PARAM_INT);
        $stmt->bindValue(":matchNo", $this->matchno, PDO::PARAM_INT);
        
        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // extract row
            // this will make $row['name'] to
            // just $name only

            //var_dump($row);

            extract($row);
                

            // update properties
            $this->match_id = $match_id;
            $this->ba_match_no = $ba_match_key;   // The Blue Alliances match code
            $this->blue_1 = $blue_1;
            $this->blue_2 = $blue_2;
            $this->blue_3 = $blue_3;
            $this->red_1  = $red_1;
            $this->red_2  = $red_2;
            $this->red_3  = $red_3;
        
            return true;
        } else {
                
            return false;
        }
    }

    // create update
    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET
            event_id=:eventid,
            comp_level=:complevel,
            match_no=:matchno,
            ba_match_key=:bamatchid,
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
        $this->ba_match_no=htmlspecialchars(strip_tags($this->ba_match_no));
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
        $stmt->bindParam(":bamatchid", $this->ba_match_no);
        $stmt->bindParam(":blue1", $this->blue_1);
        $stmt->bindParam(":blue2", $this->blue_2);
        $stmt->bindParam(":blue3", $this->blue_3);
        $stmt->bindParam(":red1", $this->red_1);
        $stmt->bindParam(":red2", $this->red_2);
        $stmt->bindParam(":red3", $this->red_3);
    
        //var_dump($query);

        echo "event_id:" . $this->event_id . "\n";
        echo "complevel:" . $this->comp_level . "\n";
        echo "matchno:" . $this->matchno . "\n";
        echo "bamatchid:" . $this->ba_match_no . "\n";

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

    // update match
    function update(){
    
        echo "Update match_id:" . $this->match_id . "\n";

        // query to insert record
        $query = "UPDATE " . $this->table_name . " SET ";
        if (!empty($this->ba_match_no)) {
            $query = $query . "ba_match_key=:bamatchkey,";
        }
        $query = $query . " 
            blue_1=:blue1,
            blue_2=:blue2,
            blue_3=:blue3,
            red_1=:red1,
            red_2=:red2,
            red_3=:red3 
            WHERE match_id=:matchid";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->match_id=htmlspecialchars(strip_tags($this->match_id));
        $this->ba_match_no=htmlspecialchars(strip_tags($this->ba_match_no));
        $this->blue_1=htmlspecialchars(strip_tags($this->blue_1));
        $this->blue_2=htmlspecialchars(strip_tags($this->blue_2));
        $this->blue_3=htmlspecialchars(strip_tags($this->blue_3));
        $this->red_1=htmlspecialchars(strip_tags($this->red_1));
        $this->red_2=htmlspecialchars(strip_tags($this->red_2));
        $this->red_3=htmlspecialchars(strip_tags($this->red_3));
    
        // bind values
        $stmt->bindParam(":matchid", $this->match_id);
        if (!empty($this->ba_match_no)) {
            $stmt->bindParam(":bamatchkey", $this->ba_match_no);
        }
        $stmt->bindParam(":blue1", $this->blue_1);
        $stmt->bindParam(":blue2", $this->blue_2);
        $stmt->bindParam(":blue3", $this->blue_3);
        $stmt->bindParam(":red1", $this->red_1);
        $stmt->bindParam(":red2", $this->red_2);
        $stmt->bindParam(":red3", $this->red_3);
    
        //var_dump($stmt);

        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;        
    }


}
