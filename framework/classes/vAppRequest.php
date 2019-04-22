<?php

    class vAppRequest{
        /**
         * VISHNU CLASS vAppRequest
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * vAppRequest
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
         *
         * @var String Module Name
         */
        var $module                    = "";
        /**
         *
         * @var String Command for Module to execute
         */
        var $command                   = "";
        /**
         *
         * @var Array Data sent with request;
         */
        var $data                      = array();
        
        /**
         *
         * @var vApplication Object for VISHNU application
         */
        var $app                       = null;
        
        
        // ---------------------------------------------------------------------
        // CONSTRUCTOR
        // ---------------------------------------------------------------------
        function __construct($a){
            $this->app = $a;
        }

        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------

        function runRequest(){
            
            // LOAD LICENSE FILE BEFORE running request for license validation in modules
            $this->app->loadLicenseFile();
            
            $r = "";
            if( substr($this->module,0,1) == 'x' ){
                $moduleFile = PATHVISHNU . "framework/modules/" . $this->module . "/" . $this->module . ".php";
            }
            else{
                $moduleFile = PATHAPP . "modules/" . $this->module . "/" . $this->module . ".php";
            }
            if(file_exists($moduleFile) ) require_once($moduleFile);
            
            $modName = $this->module;
            
            if(class_exists($modName)  ){
                $o = new $modName();
                $o->app = $this->app;
				
                $r = $o->runAppRequest($this->command, $this->data);

                if( $this->app->session->user->language ){
                    $r = replaceLanguageVars( $r, $this->app->session->user->language );
                }

                if(is_object($r)){
                    if( get_class($r) == "vError" ){
                        $r = $r->getJson();
                    
                }
                
            }
            return($r);
        }
        
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------


    }

}
