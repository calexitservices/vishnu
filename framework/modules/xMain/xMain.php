<?php

    class xMain extends vModuleBase{
        /**
         * VISHNU CLASS mod1
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * mod1
         * 
         * @package Framework
         * @subpackage Classes
         * @since 1.0
         * 
         * @version 1.0
         * @author Florian Rieder
         * @copyright (c) 2013, Florian Rieder 
         */
  
        // ---------------------------------------------------------------------
        // VARIABLE DECLARATION
        // ---------------------------------------------------------------------
        
        
        // ---------------------------------------------------------------------
        // CONSTRUCTOR
        // ---------------------------------------------------------------------
        function __construct(){
            
        }

        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------
        function runAppRequest($command, $data){
            
            switch($command){
                case 'login':
                    return($this->getCodeLogin($data));
                    break;
                case 'runlogin':
                    return($this->commandLogin($data));
                    break;
                case 'logout':
                    return($this->commandLogout());
                    break;
                case 'appStart':
                    return($this->commandStart());
                    break;
                case 'clearOldSessionFiles':
                    return($this->clearOldSessionFiles());
                    break;
                case 'clearSessions':
                    return($this->clearSessions());
                    break;
                default:
                    return("xMain:" . $command);
                    break;
            }
            
            //funtion for prepareRedirect
            //            prepareResult
            //            prepareResultInContainer
            
        }
        
        function runPageRequest($pr) {
            $r = new vPageRequestResponse();
            
            //$r->template = "cbc";
            
            if($pr->command == 'login' ){
                $r->template = 'login';
            }
            
            switch($pr->command){
                case 'logout':
                case 'clearOldSessionFiles':
                case 'clearSessions':
                    $r->directResponse = true;
                    break;
            }
            
            return($r);
        }

        
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------
            
        private function getCodeLogin($data){
            $h = "";
            $loginFileName = PATHAPP . "app/html/login.html";
            
            if(file_exists($loginFileName)){
                $h = file_get_contents($loginFileName);
            }
            
            $thisUser = "";
            
            if(defined("SAVELOGINUSER")){
                if(SAVELOGINUSER){
                    $thisUser = arrVal($_COOKIE, "lastLoginUser", "");
                }
            }

            $h = str_replace("{username}", $thisUser, $h);
            
            return($h);
        }
        
        
        private function commandLogin($data){
            global $vischnuApplication;

            $retVal =  $this->prepareRedirect('/app/xMain/login?error=' . vError::AUTHENTICATION ) ;

            if( isset($data["uname"]) && isset($data["upwd"]) ){
                if( $vischnuApplication->session->startSession($data["uname"], $data["upwd"], VAPPKEY) ){

                    $vischnuApplication->loadLicenseFile();

                    $retVal =  $this->prepareRedirect('/app/xMain/login?error=' . vError::DEFERROR );

                    if(function_exists('loginFunction')){
                        $tmpRet = loginFunction($vischnuApplication);

                        if($tmpRet === true){
                            $retVal =  $this->prepareRedirect(APPHOME);
                        }
                        else{
                            if( isJson($retVal) ){
                                $retObj = json_decode($tmpRet);
                                if( isset($retObj->error) ){
                                    if($retObj->error){
                                        $retVal =  $this->prepareRedirect('/app/xMain/login?error=' . $retObj->error );
                                    }
                                    else{
                                        
                                        if(defined("SAVELOGINUSER")){
                                            if(SAVELOGINUSER){
                                                setcookie("lastLoginUser", $data["uname"], 0, "/", "", false, true);
                                            }
                                        }
                                        
                                        $retVal =  $this->prepareRedirect(APPHOME);
                                    }
                                }
                            }
                        }
                    }

                }

            }
            
            return($retVal);
            
        }
        
        private function commandLogout(){
            
            global $vischnuApplication;
            
            $vischnuApplication->session->endSession();
            header("location: /");
            
        }
        
        private function commandStart(){
            $this->app->appStart();
        }
        

        private function clearOldSessionFiles(){
            
            $thisPath = PATHAPP . "sessions/";
            $minLimit = 600;
            
            echo "<h1>clearOldSessionFiles</h1>";
            
              if ($handle = opendir($thisPath)) {
                 while (false !== ($file = readdir($handle))) {
                    if ((time()-filemtime($thisPath.$file)) > $minLimit * 60) {  
                       if (strpos($file, ".") === false){
                          echo ($file . ": " . strpos($file, ".") . "<br>");
                          unlink($thisPath.$file);
                       }
                    }
                 }
               }        
               closedir($handle);
               echo "<br>Done.<br>";

            //exec($thisCmd);

        }  
        private function clearSessions(){
            
            $thisPath = PATHAPP . "sessions/";
                
            echo "<h1>clearSessions</h1>";
            
              if ($handle = opendir($thisPath)) {
                 while (false !== ($file = readdir($handle))) {
                   if (strpos($file, ".") === false){
                      echo ($file . ": " . strpos($file, ".") . "<br>");
                      unlink($thisPath.$file);
                   }
                 }
               }        
               closedir($handle);
               echo "<br>Done.<br>";

            //exec($thisCmd);

        }        
        
        
}

