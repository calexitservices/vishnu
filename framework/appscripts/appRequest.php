<?php

    require_once(PATHAPP . '/app/config.php');
    require_once(PATHVISHNU . "vishnu.php");

    $vischnuApplication = new vApplication();
    
    $requestParams = $_REQUEST;
    $thisData = array();
    foreach($requestParams as $k=>$v){
        // remove all parameters starting with "_" due to google analytics request parameters
        // if(substr($k, 0, 1)!= "_"){
        //
        // remove all parameters with non-numeric keys
        //if(is_numeric($k)){
        if(substr($k, 0, 1)!= "_"){
            $thisData[$k] = $v;
        }
    }
    unset($thisData["PHPSESSID"]);    // REMOVE PHP SESSION ID FROM internal data
    
    $resp = $vischnuApplication->runRequest($thisData);

    //header("Content-Type: application/json");
    header("Expires: on, 01 Jan 1970 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    
    print_r($resp);
    