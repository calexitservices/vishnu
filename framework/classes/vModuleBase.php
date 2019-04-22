<?php

    class vModuleBase{
        /**
         * VISHNU CLASS vModuleBase
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * vModuleBase
         * 
         * Base class for all module classes
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
         * @var vApplication Object for running VISHNU application
         */
        var $app = null;
        /**
         *
         * @var Array for Navigation Elements
         */
        var $navElements = array();
        
        // ---------------------------------------------------------------------
        // CONSTRUCTOR
        // ---------------------------------------------------------------------
        function __construct(){
            
        }

        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------
        
        /**
         * 
         * @param String $command Command to be executed in Module
         * @param Array $data Array of data to be passed to command
         * @return String
         */
        function runAppRequest($command, $data){
            ("runAppRequest not imlemented for class " . get_class($this) );
        }
        
        function setApp($app){
            $this->app = $app;
        }
        
        
        /**
         * 
         * @param vPageRequest $pr vPageRequest object with all request data
         * @return vPageRequestResponse
         */
        function runPageRequest($pr){
            return("runPageRequest not imlemented for class " . get_class($this) );
        }

        function prepareRedirect($url){
            //return($url . "||");
            $e = new vError();
            $e->setError(vError::FORCENAVIGATE);
            $e->description = "$url";
            $e->details = "";
            
            return($e->getJson());
        }

        function getModuleName(){
            return("getModuleName not imlemented for class " . get_class($this) );
        }

        /**
         * 
         * @param String $caption Captoion for the navigation element
         * @param String $request Request to be called within the Menu
         * @param String $requiredRight Necessary right key for navigation element to be displayed
         */
        function addNavElement($caption, $link, $requiredRight=""){
            $n = new vNavElement();
            $n->caption       = $caption;
            $n->link          = $link;
            $n->requiredRight = $requiredRight;
            array_push($this->navElements, $n);
            
            return($n);
        }

        function addNavRequestElement($caption, $target, $module, $function, $data="", $requiredRight=""){
            $n = new vNavElement();
            $n->caption       = $caption;
            $n->target        = $target;
            $n->module        = $module;
            $n->function      = $function;
            $n->data          = $data;
            $n->requiredRight = $requiredRight;
            array_push($this->navElements, $n);
            
            return($n);
        }
        


        
        /**
         * 
         * @param vPageRequest $pReq Page Request Object of current page
         * @return String HTML Code for Navigation
         */
        function buildNavigation($pReq){
            $h = "";

            if( count($this->navElements) ){
                $h .= $this->buildNavLevel($this, 1);
            }

            
            $h = str_replace("#data1#", $pReq->data[0], $h);
            $h = str_replace("#data2#", $pReq->data[1], $h);
            $h = str_replace("#data3#", $pReq->data[2], $h);
            
            return($h);
        }
        
        /**
         * 
         * @param vNavElement $parent
         * @param int $level Depth of navigation
         * @return String HTML Code for navigation level
         */
        private function buildNavLevel($parent, $level=1){
            $h = "";

            $itemTemplate  = "<li><a href='{url}' data-target='{target}' data-module='{module}' data-function='{function}' data-val='{data}'  >{caption}</a>{subnavigation}</li>";
            $itemWrapper   = "<ul class='navlevel l$level'>{items}</ul>";
            $itemCode   = "";

            $itemTemplateFile = PATHAPP . "app/html/nav".$level."Item.html";
            if(file_exists($itemTemplateFile)){
                $itemTemplate  = file_get_contents($itemTemplateFile);
            }
            $wrapperTemplateFile = PATHAPP . "app/html/nav".$level."Wrapper.html";
            if(file_exists($wrapperTemplateFile)){
                $itemWrapper  = file_get_contents($wrapperTemplateFile);
            }

            
            foreach($parent->navElements as $n){
                
                $caption   = $n->caption;
                $link      = $n->link;
                $reqRight  = $n->requiredRight;
                
                if($this->app->checkRight($reqRight) ){
                    $thisElement = $itemTemplate;
                    $thisElement = str_replace("{caption}", $caption, $thisElement);
                    $thisElement = str_replace("{url}", $link, $thisElement);

                    $thisElement = str_replace("{target}", $n->target, $thisElement);
                    $thisElement = str_replace("{module}", $n->module, $thisElement);
                    $thisElement = str_replace("{function}", $n->function, $thisElement);
                    $thisElement = str_replace("{data}", $n->data, $thisElement);
                    
                    $subnavCode  = "";
                    if( count($n->navElements) ){
                        $subnavCode = $this->buildNavLevel($n, ($level+1) );
                    }
                    
                    $thisElement  = str_replace("{subnavigation}", $subnavCode, $thisElement);
                    $itemCode .= $thisElement;
                }
                
            }
            
            $h = $itemWrapper;
            $h = str_replace("{items}", $itemCode, $h);
            
            return($h);
        }
        
        
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------
  
        
        
    }

