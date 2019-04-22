<?php
/**
 * Class clsCmsDb
 *
 * long description
 * @package classes
 * @author Florian Rieder
 * @version 1.0
 */

class vDb {
    
    // CLASS VARIABLES
    private $connection          = null;
    private $connected           = 0;
    
	var $host                = "";
	var $user                = "";
	var $password            = "";
	var $database            = "";
	
    function __construct($host="", $user="", $pwd="", $db=""){
		if(defined('DBHOST')) $this->host = DBHOST;
		if(defined('DBUSER')) $this->user = DBUSER;
		if(defined('DBPWD'))  $this->password = DBPWD;
		if(defined('DBNAME')) $this->database = DBNAME;

		if($host) $this->host     = $host;
		if($user) $this->user     = $user;
		if($pwd)  $this->password = $pwd;
		if($db)   $this->database = $db;
    }
    
    /**
     * connects to a mysql database using the DBHOST, DBUSER, DBPWD parameters
     */
    public function connect(){
        //$this->connection=mysql_connect(DBHOST, DBUSER, DBPWD);
        //mysql_select_db(DBNAME, $this->connection);
        
        $this->connection = mysqli_connect( $this->host, $this->user, $this->password, $this->database);
        if($this->connection) $this->connected = 1;
    }

    public function disconnect(){
        mysqli_close($this->connection);
        $this->connection = null;
        $this->connected = 0;
    }

    
    /**
     * Exexutes an SQL statment for the current connection
     * @param String $sql SQL statement to be executed
     * @param Boolean $showError force echo output of error messages
     * @return vError::SQLERROR in case of error
     */
    public function execute($sql, $showError=true){
        
        if(!$this->connected) $this->connect();
        
        $sql = utf8_decode($sql);
        
        $exRet = mysqli_query($this->connection, $sql);
        
        $ret = vError::NOERROR;
        
        if( mysqli_errno($this->connection) ){
            $ret = vError::SQLERROR;
        }

        if( mysqli_errno($this->connection) and $showError){
            echo "SQL ERROR: " . mysqli_errno($this->connection) . " - " . mysqli_error($this->connection). "<hr>$sql<hr>";
        }
        
        return( $ret );

    }
    
    /**
     * Executes an SQL statement for the current session and returns the resultset
     * @param type $sql SQL statment to be executed
     * @param type $showError force echo output of error messages
     * @return resultset
     * 
     */
    public function query($sql, $showError=true){
        if(!$this->connected) $this->connect();
        $r = mysqli_query($this->connection, $sql);
        
        if( mysqli_errno($this->connection) ){
            $r = vError::SQLERROR;
        }
        
        if( mysqli_errno($this->connection) and $showError){
            echo "SQL ERROR: " . mysqli_errno($this->connection) . " - " . mysqli_error($this->connection). "<hr>$sql<hr>";
        }
        
        return($r);
    }
    
    /**
     * Returns the last inserted ID for this connection
     * @return int
     */
    public function getLastId(){
        $r = mysqli_insert_id($this->connection);
        return($r);
    }
    
    /**
     * Returns the row count for a resultset
     * @param resultset $res
     * @return int
     */
    public function getNumRows($res){
        $r = mysqli_num_rows($res);
        return($r);
    }
    
    /**
     * Returns an object of the current record of a resultset and moves to the next record. Returns false if no record available.
     * @param resultset $res
     * @return object
     */
    public function fetchObject($res){
        $r = mysqli_fetch_object($res);
        return($r);
    }

    /**
     * Returns an array of the current record of a resultset and moves to the next record. Returns false if no record available.
     * @param resultset $res
     * @return object
     */    public function fetchArray($res){
        $r = mysqli_fetch_assoc($res);
        return($r);
    }

    /**
     * Escapes a given string according to current connection settings.
     * @param String $str
     * @return String
     */
    public function escapeString($str){
        if(!$this->connected) $this->connect();
        $r = mysqli_real_escape_string($this->connection, $str);
        return($r);
    }
    
    /**
     * Fills a result containing multiple rows into a multidimensional array
     * @param resultset $result
     * @param String $idColumn
     * @return Array
     */
    public function result2Array($result, $idColumn=""){
        $retArray = Array();
        
        while($row = $this->fetchArray($result)){
            if(is_array($row)){
                $retLine  = Array();
                $lineID   = 0;
                foreach($row as $col=>$val){
                    if($col == $idColumn){
                        $lineID = $val;
                    }
                    else{
                        if( is_null($val) )$val = "";
                        $retLine[$col] = utf8_encode($val);
                    }
                }
                
                if($idColumn){
                    $retArray[$lineID] = $retLine;
                }
                else{
                    array_push($retArray, $retLine);
                }
            }
        }
        
        return($retArray);
    }
    
    /**
     * Returns an array of IDs and field values of a resultset
     * @param resultset $result
     * @param Strimg $idColumn
     * @param String $valColumn
     * @return array
     */
    public function result2List($result, $idColumn, $valColumn){
        $retArray = Array();

        while($row = $this->fetchArray($result)){
            if(is_array($row)){
                $i = $row[$idColumn];
                $v = $row[$valColumn];
                $retArray[$i] = $v;
            }
        }
        
        return($retArray);
    }    
    
    /**
     * Saves a record with all provided values. if the id of the record is not set a record is being inserted into the table. Returns the id of record.
     * @param String $tbl Table Name
     * @param String $idField Field name of ID value
     * @param array $values of Values (where key is the field name)
     * @return int
     */
    public function saveRecord($tbl, $idField, $values, $overrideWhere=""){
        
        $d = $this;
        
        $sql   = "";
        $retID = 0;
        
        if( !isset($values[$idField]) ) {
            return(false);
        }
        
        if($values[$idField]){
            $sql  = " UPDATE $tbl SET ";
            foreach($values as $k=>$v){
                if($k != $idField){
                    if(is_null($v)){
                        $v = 'null';
                    }
                    elseif( !is_numeric($v)){
                        $v = utf8_encode($v);
                        $v = "'" . $d->escapeString($v) . "'";
                    }
                    
                    $sql .= " $k = $v,";
                    
                }
            }
                $sql = rtrim($sql, ",");
				
                $sql .= " WHERE " ;
				if( $overrideWhere ){
					$sql .= $overrideWhere;
				}
				else{
					$sql .= " $idField = " . $values[$idField];
				}
                
            $retID = $values[$idField];
                
        }
        else{
            $fieldList = "";
            $valueList = "";
            
            foreach($values as $k=>$v){
                if($k != $idField){
                    if(is_null($v)){
                        $v = 'null';
                    }
                    elseif( !is_numeric($v)){
                        $v = "'" . $d->escapeString($v) . "'";
                    }
                    $fieldList .= "$k,";
                    $valueList .= "$v,";
                }
            }
                
            $fieldList = rtrim($fieldList, ",");
            $valueList = rtrim($valueList, ",");
            
            $sql  = " INSERT INTO $tbl($fieldList) VALUES($valueList)";
        }

        $d->execute($sql);
        
        if( !$retID ) $retID = $d->getLastId();
        
        return($retID);
    }
    
    /**
     * Returns the value of the field 'result' from a sql query
     * @param String $sql SQL query
     */
    public function getResultFromQuery($sql){
        
        $retVal = false;
        
        $db  = new vDb();
        $res = $db->query($sql);
        
        if($row = $db->fetchArray($res)){
            $retVal = arrVal($row, "result", false);
        }
        
        return($retVal);
        
    }
    
    public function generatePublicKey($table, $idField, $id, $keyField="publicKey", $length=32){
        do {
            $k   = generateRandomString($length);
            $sql = "SELECT * FROM $table WHERE $keyField='$k'";
            $res = $this->query($sql);
        } while($this->getNumRows($res));
        
        $sql = "UPDATE $table SET $keyField='$k' WHERE $idField=$id";
        $this->execute($sql);
        return($k);
    }
    
}

