<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of vDbEdit
 *
 * @author fr
 * 
 */

class vDbEdit {
    //put your code here
    
    /**
     *
     * @var vDb DB Object
     */
    
    private $dbc                = null;
    private $dbStructure        = array();
    private $data               = array();
    private $table              = "";
    private $keyColumn          = "";
    
    public function __construct($dbTable){
        
        $this->table = $dbTable;
        $this->dbc = new vDb();

        $sql = "SHOW FIELDS FROM $dbTable";
        $res = $this->dbc->query($sql);        
        
        $this->dbStructure = $this->dbc->result2Array($res, "Field");
        
        $sql = "SHOW INDEX FROM $dbTable WHERE Key_name='PRIMARY'";
        $res = $this->dbc->query($sql);  
        
        if($row = $this->dbc->fetchArray($res)){
            $this->keyColumn = $row["Column_name"];
        }
                
    }

    public function addData($field, $data){
        $this->data[$field] = $data;
    }
    
    public function save(){
        
        $keyCol  = $this->keyColumn;
        $keyData = $this->data[$this->keyColumn];
        $sql     = "";
        
        if( $keyData ){
            // EXISTING RECORD
            
            foreach($this->data as $k=>$v){
                
                $v = $this->dbc->escapeString($v);
                
                if( strlen($sql) ) $sql .=", ";
                $sql .= "$k='$v'";
            }
            
            $sql  = " UPDATE " . $this->table . " SET " . $sql;
            $sql .= " WHERE $keyCol=$keyData";
        }
        else{
            // NEW RECORD
            
            $strValues  = "";
            $strColumns = "";

            foreach($this->data as $k=>$v){
                
                $v = $this->dbc->escapeString($v);
                
                if( strlen($strValues) )  $strValues   .=", ";
                if( strlen($strColumns) ) $strColumns  .=", ";
                
                $strColumns  .= $k;
                $strValues   .= "'$v'";
            }            
            
            $sql  = " INSERT INTO " . $this->table;
            $sql .= " ($strColumns)";
            $sql .= " VALUES($strValues)";
        }
        
        $res = $this->dbc->execute($sql, false);
                
        return($res);
        
    }

    public function load($id){

    }
    
    
}

