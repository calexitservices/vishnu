<?php

    class vSession {
        /**
         * VISHNU CLASS vSession
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * vSession
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
        
        var $username        = "";
        var $fullname        = "";
        var $userlanguage    = "";
        var $sessionkey      = "";
        
        /**
         *
         * @var vUser User Object
         */
        var $user            = null;
        
        // ---------------------------------------------------------------------
        // CONSTRUCTOR
        // ---------------------------------------------------------------------
        function __construct(){
            $this->user = new vUser();
        }

        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------

        function startSession($u, $p, $appKey){            
            if( $this->verifyCredentials($u, $p) ){

                $sessKey = $u;
                if(defined("MULTILOGON")){
                    if(MULTILOGON){
                        $sessKey = generateRandomString(18);
                    }
                }
                
                $myFile = PATHAPP . "sessions/" . $sessKey;
                
                $fh = fopen($myFile, 'w') or die("can't write session file: $u");
                $stringData  = "time:" . date("Y-m-d H:i:s") . "\n";
                $stringData .= "ip:" . $_SERVER["REMOTE_ADDR"] . "\n";
                $stringData .= "phpsession:" . session_id() . "\n";
                $stringData .= "user:" . $u . "\n";
                $stringData .= "application:" . $appKey . "\n";

                fwrite($fh, $stringData);
                fclose($fh);                
                
                $usr = new vUser();
                $usr->load($u);

                //setcookie($this->getSessionCookieName(), $sessKey, 0, "/", "", false, true);
                $_SESSION[$this->getSessionCookieName()] = $sessKey;
                
                return(true);
            }
            else{
                return(false);
            }
            
        }
        
        function endSession(){
            
            $cookieName = $this->getSessionCookieName();

            $sessKey = arrVal($_SESSION, $cookieName, "");
            
            if($sessKey){
                $sessFile = PATHAPP . "sessions/" . $sessKey;
                
                if(file_exists($sessFile)){
                    unlink($sessFile);
                }
            }

            //setcookie($cookieName, "-ENDED", 0, "/", "", false, true);
            $_SESSION[$cookieName] = "-ENDED";
            unset($_SESSION[$cookieName]);
            
        }
        
        function checkSession($appKey){
            
            $sessKey = "";       
            $retVal = 0;
            
            $checkPhpSession = true;
            if(defined("IGNOREPHPSESSION")){
                if(IGNOREPHPSESSION){
                    $checkPhpSession = false;
                }
            }
            
            $cookieName = $this->getSessionCookieName();
            if( isset($_SESSION[$cookieName]) ){

                $sessKey = $_SESSION[$cookieName];

                $fn = PATHAPP . "sessions/" . $sessKey;
                
                if($sessKey && file_exists($fn)){
                    
                    $sessionFile = file_get_contents($fn);
                    $sessionData = datafile2Array($sessionFile);
                    
                    if( $this->user->load($sessionData["user"]) ){
                        $retVal = 1;
                    }
                    
                }

                if($retVal==1){
                    if ( arrVal($sessionData, "ip", "-") != $_SERVER["REMOTE_ADDR"] ) $retVal  = 0;   // verify same ip address
                    
                    if($checkPhpSession){
                        if ( arrVal($sessionData, "phpsession", "-") != session_id() ) $retVal = 0;   // verify same php session
                    }
                    
                    if( arrVal($sessionData, "application", "-") != $appKey )          $retVal = 0;   // check that session is of same application
                    
                }
            }

            if(!$retVal){
                $this->endSession();
            }
            else{
                touch($fn);
            }
            
            return($retVal);
        }
        
        function verifyCredentials($u, $p){
            // RETURN CODES: 1 OK
            //               0 No Match

            $fn = PATHAPP . "users/" . $u;
            
            $retVal = false;
            
            if($u && file_exists($fn)){
                $fc = file_get_contents($fn);
                $fileData = datafile2Array($fc);
                
                $userPwd  = "-";
                if( isset($fileData["password"]) ) $userPwd  = $fileData["password"];
                
                if( $userPwd == md5($p) ){
                    $retVal = true;
                }
            }
            
            return($retVal);
        }
        
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------
        
        private function getSessionCookieName(){
            $sessionCookie = "vSession";
            if( defined("VAPPKEY") ){
                $sessionCookie = strtolower(VAPPKEY) . "VSession";
            }
            return($sessionCookie);
            
        }
        
}

