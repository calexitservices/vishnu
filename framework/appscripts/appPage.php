<?php
    
    //load app config settings
    require_once(PATHAPP . '/app/config.php');
    //load framework files
    require_once(PATHVISHNU . "vishnu.php");

    //check for vishnu updates
    if(!file_exists(PATHAPP . 'startup.log')){
        include( PATHVISHNU . 'scripts/vishnu_start.php' );
        
        if(file_exists(PATHAPP . 'app/appStart.php')){
            include( PATHAPP . 'app/appStart.php' );
        }
        
    }
    
    $pReq        = new vPageRequest();
    $pReqResp    = $pReq->prepareRequest($_REQUEST);

    if($pReqResp){
        
        $tcontent = "";
        
        $vischnuApplication = new vApplication();

        if( $pReq->command != 'runlogin' && $pReq->command != 'login' && substr($pReq->command,0,4)!='xnl_' ){
        
            if(!$vischnuApplication->session->checkSession(VAPPKEY)){
                header("Location: /app/xMain/login");
            }
            
        }

        if($pReqResp->directResponse){
            $rdata["data"]        = $pReq->data[0];
            $rdata["data2"]       = $pReq->data[1];
            $rdata["data3"]       = $pReq->data[2];
            $rdata["module"]      = $pReq->module;
            $rdata["command"]     = $pReq->command;
            
            $vischnuApplication->runRequest($rdata);            
        }
        else{
            
            $tfileApp    = PATHAPP    . "app/templates/"  . $pReqResp->template . ".html";
            $tfileVishnu = PATHVISHNU . "resources/html/" . $pReqResp->template . ".html";

            $tcontent    = "<h1>TEMPLATE NOT FOUND $pReqResp->template </h1>";
            $tcontent   .= "MODULE: " . $pReq->module . "<br>";
            $tcontent   .= "COMMAND: " . $pReq->command . "<br>";

            $tcontent   .= "TEMPLATE: " . $pReqResp->template . "<br>";

            if(file_exists($tfileApp) ){
                $tcontent = file_get_contents($tfileApp);
            }
            else{
                if( file_exists($tfileVishnu) ){
                    $tcontent = file_get_contents($tfileVishnu);
                }
            }
            
            // create application and module objects
            $navCode    = "";
            $modName    = $pReq->module;
            $vischnuApplication->loadModule($modName);
            $thisModule = new $modName;
            
            $thisModule->app = $vischnuApplication;
            
            // build navigation code
            $navCode = $thisModule->buildNavigation($pReq);
            
            // replace variables
            $tcontent = str_replace("#module#", $pReq->module, $tcontent);
            $tcontent = str_replace("#command#", $pReq->command, $tcontent);
            $tcontent = str_replace("#data1#", $pReq->data[0], $tcontent);
            $tcontent = str_replace("#data2#", $pReq->data[1], $tcontent);
            $tcontent = str_replace("#data3#", $pReq->data[2], $tcontent);

            $tcontent = str_replace("#navigation#", $navCode, $tcontent);
            $tcontent = str_replace("#userFullname#", $vischnuApplication->session->user->fullname , $tcontent);
            $tcontent = str_replace("#username#", $vischnuApplication->session->user->username , $tcontent);
            
            if( $vischnuApplication->session->user->language ){
                $tcontent = replaceLanguageVars( $tcontent, $vischnuApplication->session->user->language );
            }
            
            if($pReqResp->script){
                $scriptCode  = "<script> \n";
                $scriptCode .= $pReqResp->script. " \n";
                $scriptCode .= "</script> \n\n";
                $scriptCode .= "</body>";
                $tcontent    = str_replace("</body>", $scriptCode, $tcontent);
            }
        }
        
    }
    else{
        $tcontent    = "<h1>MODULE NOT FOUND</h1>";
        $tcontent   .= "MODULE: " . $pReq->module . "<br>";
        $tcontent   .= "COMMAND: " . $pReq->command . "<br>";
    }
    
    echo $tcontent;
    