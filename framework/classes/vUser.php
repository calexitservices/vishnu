<?php

    class vUser{
        /**
         * VISHNU CLASS vUser
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * vUser
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
        
        public $username     = "";
        public $fullname     = "";
        public $language     = "";
        public $usergroup    = "";
        public $email        = "";
        public $data         = array();
        public $rights       = array();
        public $modules      = array();
        
        // ---------------------------------------------------------------------
        // CONSTRUCTOR
        // ---------------------------------------------------------------------
        function __construct(){
            
        }

        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------
        
        function load($vUser){
            
            $retVal = false;
            
            $fn = PATHAPP . "users/" . $vUser;
            if(file_exists($fn)){
                $fc = file_get_contents($fn);
                $fileData = datafile2Array($fc);

                $this->username = $vUser;

                if( isset($fileData["fullname"]) )  $this->fullname      = $fileData["fullname"];
                if( isset($fileData["language"]) )  $this->language      = $fileData["language"];
                if( isset($fileData["usergroup"]) ) $this->usergroup     = $fileData["usergroup"];
                if( isset($fileData["email"]) )     $this->email         = $fileData["email"];
                if( isset($fileData["right"]) )     $this->rights        = $fileData["right"];
                if( isset($fileData["module"]) )    $this->modules       = $fileData["module"];
                
                if( !is_array($this->rights) ){
                    $onlyRight = $this->rights;
                    $this->rights = array();
                    $this->rights[] = $onlyRight;
                }
                
                if( !is_array($this->modules) ){
                    $onlyMod = $this->modules;
                    $this->modules = array();
                    $this->modules[] = $onlyMod;
                }
                
                unset($fileData["password"]);
                unset($fileData["fullname"]);
                unset($fileData["language"]);
                unset($fileData["usergroup"]);
                unset($fileData["email"]);
                unset($fileData["right"]);
                unset($fileData["module"]);
                
                $this->data = $fileData;

                // run usergroup file if exists
                $ugrpFile = PATHAPP . "usergroups/" . $this->usergroup . ".php";
                
                if(file_exists($ugrpFile) ){
                    require_once($ugrpFile);
                }
                
                $retVal = true;
                
            }
            
            return($retVal);
                     
        }
        
        function save($setPasswortTo=""){
            
            if(!$this->username){
                return(false);
            }
            
            $userFile = PATHAPP."users/" . $this->username;
            
            $fileData = array();
            $origData = array();
            
            if($userFile){
                if(file_exists($userFile)){
                    $fileContent = file_get_contents($userFile);
                    $origData = datafile2Array($fileContent);
                }
            }
            
            $fileData["email"] = $this->email;
            $fileData["fullname"] = $this->fullname;
            $fileData["language"] = $this->language;
            $fileData["usergroup"] = $this->usergroup;

            $fileData["module"] = $this->modules;
            $fileData["right"] = $this->rights;
            
            $fileData = array_merge($fileData, $this->data);
            
            $existingPwd = arrVal($origData, "password", "");
            if(!$existingPwd){
                if(!$setPasswortTo) $setPasswortTo = generateRandomString(9);
                $fileData["password"] = md5($setPasswortTo);
            }
            else{
                $fileData["password"] = $existingPwd;
            }
            
            array2Datafile($userFile, $fileData);
            
            return(true);
            
        }
        
        function changeUsername($newUsername){
            $oldFile = PATHAPP."users/" . $this->username;
            $newFile = PATHAPP."users/" . $newUsername;
            rename($oldFile, $newFile);
            $this->username = $newUsername;
        }
        
        function getSetting($key, $default=""){
            $retval = $default;
            $key    = "setting." . $key;
            
            if(isset($this->data[$key])){
                $retval = $this->data[$key];
            }
            
            return($retval);
        }
        
        function saveSetting($key, $value){
            
            $fn = PATHAPP . "users/" . $this->username;

            $fc = file_get_contents($fn);
            $fileData = datafile2Array($fc);
            $fileData["setting." . $key] = $value;
            array2Datafile($fn, $fileData);

            
/*            
            if(file_exists($fn)){
                $fc = file_get_contents($fn);
                $fileData = datafile2Array($fc);

                $key = "setting." . $key;
                $fileData[$key] = $value;

                ksort($fileData);
                $fileContent = "";
                
                foreach( $fileData as $k=>$v ){
                    if(is_array($v)){
                        foreach( $v as $vv ){
                            $fileContent .= $k . ":" . $vv . "\n";
                        }
                    }
                    else{
                        $fileContent .= $k . ":" . $v . "\n";
                    }
                }

                file_put_contents($fn, $fileContent);                 
                
            }            
*/          
            
        }
        
        function setPassword($newPwd){

            $fn = PATHAPP . "users/" . $this->username;
            if(file_exists($fn)){
                $fc = file_get_contents($fn);
                $fileData = datafile2Array($fc);
                $fileData["password"] = md5($newPwd);
                array2Datafile($fn, $fileData);
            }
            
        }
        
        function setLanguage($newLang){
            
            $fn = PATHAPP . "users/" . $this->username;
            if(file_exists($fn)){
                $fc = file_get_contents($fn);
                $fileData = datafile2Array($fc);
                $fileData["language"] = $newLang;
                array2Datafile($fn, $fileData);
            }
            
        }
                
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------
        
        
    }

