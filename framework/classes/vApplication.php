<?php

   class vApplication{
        /**
         * VISHNU CLASS vApplication
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * vApplication
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
       
       /**
        * @var vVishnu Vishnu Framework Object
        */
        var $vishnu             = null;

       /**
        * @var vAppRequest Request Object Currently being executed
        */
        var $currentRequest     = null;

       /**
        * @var vSession Vishnu Session Object
        */
        var $session            = null;
        
       /**
        * @var array() application settings array
        */
        var $settings           = array();
        
       /**
        * @var array() application license array
        */
        var $license            = array();
        
        // ---------------------------------------------------------------------
        // CONSTRUCTOR
        // ---------------------------------------------------------------------
        function __construct(){
            $this->vishnu          = new vVishnu();
            $this->currentRequest  = new vAppRequest($this);
            $this->session         = new vSession();
            
            if(!isset($_SESSION)){
                session_start();
            }
            
        }

        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------
        
        function runRequest($req){
            
            $this->readRequest($req);
            if( $this->currentRequest->command == 'login' or $this->currentRequest->command == 'runlogin' or $this->session->checkSession(VAPPKEY) or substr($this->currentRequest->command,0,4)=='xnl_' ){
                $resp = $this->currentRequest->runRequest();
            }
            else{
                //$resp = "session invalid<script>alert('session invalid')</script>";
                //$resp = "body##session invalid<script>alert('session invalid')</script>";

                
                $resp = new vError();
                $resp->setError(-9801);
                $resp = $resp->getJson();
            }

            return($resp);
            
            
        }
        
        
       function appStart(){
            include(PATHVISHNU . "scripts/vishnu_start.php");
            
            if(file_exists(PATHAPP . 'app/appStart.php')){
                include( PATHAPP . 'app/appStart.php' );
            }
            
        }
        
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------
        
        private function readRequest($req){
            
            if( isset($req["module"]) and isset($req["command"]) ){
            
                $this->currentRequest->module        = $req["module"];
                $this->currentRequest->command       = $req["command"];

                $reqData = $req;
                unset($reqData["module"]);
                unset($reqData["command"]);

                $this->currentRequest->data          = $reqData;
                
                
            }
        }
        
        public function getSetting($module, $setting, $default=""){
            
            if( !isset($this->settings[$module]) ) $this->loadSettingsFile($module);
            
            $retVal = $default;
            if( isset($this->settings[$module][$setting]) ) $retVal = $this->settings[$module][$setting];
            return($retVal);  

        }
        
        public function saveSetting($module, $setting, $value){
            // make sure all module settings are loaded
            $s = $this->getSetting($module, $setting);
            
            // set setting value
            $this->settings[$module][$setting] = $value;
            $this->saveSettingsFile($module);
        }
        
        private function loadSettingsFile($module){
            $settingFile = PATHDATA."_settings/$module.settings";
            if(file_exists($settingFile) ){
                $settingsContent = file_get_contents($settingFile);
                $this->settings[$module] = datafile2Array($settingsContent);
            }
            
        }
        
        private function saveSettingsFile($module){
            
            $settingFile = PATHDATA."_settings/$module.settings";
            $fileContent = "";
            
            ksort($this->settings[$module]);
            
            foreach( $this->settings[$module] as $k=>$v ){
                $fileContent .= $k . ":" . $v . "\n";
            }
            
            file_put_contents($settingFile, $fileContent);            
            
        }
        
        public function loadLicenseFile(){
            
            $licenseFile = PATHAPP . "data/license";
            
            if(defined('PATHDATA')){
                $licenseFile = PATHDATA."license";
            }
            
            if(file_exists($licenseFile) ){
                $licenseContent = file_get_contents($licenseFile);
                $this->license = datafile2Array($licenseContent);
            }
            
        }
        
        function loadModule($mod){
            
            $modFile = PATHAPP . "modules/$mod/$mod.php";
            if(file_exists($modFile)){
                require_once($modFile);
                return(true);
            }
            else{
                return(false);
            }
            
        }
        
        function checkRight($right){
            
            $retVal = false;
            
            if($right == ""){
                $retVal = true;
            }
            if(in_array( $right, $this->session->user->rights ) ){
                $retVal = true;
            }
            
            return($retVal);
            
        }
        
        
    }
