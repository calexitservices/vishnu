<?php

    class xTesting extends vModuleBase{
        /**
         * VISHNU CLASS contadox
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * contadox
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
        function xTesting(){
            
        }
        
        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------
        function runAppRequest($command, $data){       
            
            $h =  __CLASS__ . "." . $command . " executed!";
            
            switch($command){
                case "classdump":
                    $h = $this->classdump($data);
                    break;
                case "info":
                    $h = $this->getInfo();
                    break;
                case "const":
                    $h = $this->getConst();
                    break;
            }
            
            return($h);
            
        }
        
        function runPageRequest($pr) {
            $r = new vPageRequestResponse();
            
            $r->template = "cbc2Clear";
            
            if(substr($pr->command, 0, 4)=="data"){
                $r->directResponse = true;
            }
            else{
                switch($pr->command){
                    case "home":
                        $r->template = "datapage";
                        break;
                }
            }
            
            return($r);
        }
        
        
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------
        
        private function classdump($data){
            $h  = "";
            
            $xModule = $data["data"];
            $xClass  = $data["data2"];
            $xID     = $data["data3"];
            
            if($xModule){
                $this->app->loadModule($xModule);
            }
            
            $h .= "<h1>VISHNU DEBUGGING: Class Dump</h1> ";
            $h .= "Module: <span style='color: red;'>$xModule</span>  - ";
            $h .= "Class: <span style='color: red;'>$xClass</span> - ";
            $h .= "ID: <span style='color: red;'>$xID</span>";
            $h .= "<hr>";

            $this->app->loadModule($xModule);
            $ob = new $xClass;
            $ob->load($xID);
            
            $h .= "<pre>" . print_r($ob, true) . "</pre>";
            
            return($h);
        }
        
        private function getInfo(){
            phpinfo();
        }
        
        private function getConst(){
            $h = print_r(get_defined_constants(true), true);
            
            $h = "<pre>$h</pre>";
            
            return($h);
        }
        
}

