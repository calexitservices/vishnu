<?php



    require_once(PATHVISHNU . "framework/includes/functions.php");

    // VISHNU APPLICATION DIRECTORIES 
    createDirStructure(PATHAPP . 'data');
    createDirStructure(PATHAPP . 'sessions');
    createDirStructure(PATHAPP . 'tmp');
    createDirStructure(PATHAPP . 'users');
    createDirStructure(PATHAPP . 'usergroups');

    // COPY DIRECTORY appFiles to application
    dirCopy(PATHVISHNU . "resources/appFiles/", PATHAPP);
    
    // START FILE
    $myFile = PATHAPP."startup.log";
    $fh = fopen($myFile, 'w');    
    fwrite($fh, "app started: " . date('Y-m-d H:i:s') );
    fclose($fh);    
    
