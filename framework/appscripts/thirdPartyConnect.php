<?php
    
    //load app config settings
    require_once(PATHAPP . '/app/config.php');
    //load framework files
    require_once(PATHVISHNU . "vishnu.php");

    $vischnuApplication = new vApplication();
    $retVal = $vischnuApplication->session->checkSession();  // MISSING: APPKEY for session
                                                             // Create functions for thirdparty, not complete
    
    if(!$retVal){
        exit();
    }
    

