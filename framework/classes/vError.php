<?php

/**
 * Description of vError
 *
 * @author fr
 */
class vError {
    
    var $error               = 0;
    var $description         = "";
    var $details             = "";
    var $data                = array();
    
    const NOERROR            = 0;
    const SQLERROR           = -9001;
    const MISSINGDATA        = -9002;
    const INVALIDDATA        = -9003;
    const DUPLICATEDATA      = -9004;
    const MODULEERROR        = -9020;
    const NORIGHTS           = -9080;
    const AUTHENTICATION     = -9081;
    const LICENSEERROR       = -9090;
    const NOSESSION          = -9801;
    const FORCENAVIGATE      = -9802;
    const DEFERROR           = -9999;
    
    function __construct($err=self::DEFERROR){
        
        $this->setError($err);

    }
    
    function getJson(){
        
        $e["error"]        = $this->error;
        $e["description"]  = $this->description;
        $e["details"]      = $this->details;
        $e["data"]         = $this->data;
        
        return( json_encode($e) );
        
    }
    
    function setError($err){

        $ex          = new Exception;
        $traceStack  = nl2br( $ex->getTraceAsString() );
        
        $this->error = $err;
        
        switch($err){
            case 0:
                $this->description = "";
                $this->details     = "";
                break;
            
            case self::SQLERROR :
                $this->description = "An SQL Error occured.";
                $this->details     = mysql_error() . "<br><br>" . $traceStack;
                break;
            case self::MISSINGDATA :
                $this->description = "Missing Data - Please fill in all required data.";
                $this->details     = "Mandatory fields are marked with an asterisk (*).";
                break;
            case self::INVALIDDATA :
                $this->description = "Invalid Data - The data entered is not correct, please review.";
                $this->details     = "";
                break;
            case self::NORIGHTS :
                $this->description = "INSUFFICIENT RIGHTS";
                $this->details     = "You do not have rights for the reqeusted functionality.";
                break;
            
            case self::DEFERROR:
            default:
                $this->description = "An Unexpected Error has occured.";
                $this->details     = $traceStack;

                break;
        }
        
    }
    
    function setData($key, $data){
        $this->data[$key] = $data;
    }
    
}

