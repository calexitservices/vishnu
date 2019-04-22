<?php

    class vPageRequest{
        /**
         * VISHNU CLASS vPageRequest
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * vPageRequest
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
         * @var String Command
         */
        var $command                   = "";
        /**
         *
         * @var Array data 
         */
        var $data                      = array();
        
        /**
         *
         * @var Array Get Parameters of Request
         */
        var $parameters                = array();

        
        
        
        // ---------------------------------------------------------------------
        // CONSTRUCTOR
        // ---------------------------------------------------------------------
        function __construct(){
            
        }

        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------

        /**
         * @return vPageRequestResponse Gets the module information to build the response of a page request
         * @param Array $req Array of all page requests
         */
        function prepareRequest($req){
            
                if( !isset($req["vModule"]) )  $req["vModule"] = "";
                if( !isset($req["vCommand"]) ) $req["vCommand"] = "";
                if( !isset($req["vData1"]) )   $req["vData1"] = "";
                if( !isset($req["vData2"]) )   $req["vData2"] = "";
                if( !isset($req["vData3"]) )   $req["vData3"] = "";

                $this->module  = $req["vModule"];
                $this->command = $req["vCommand"];
                
                if($this->command == 'index.php') $this->command="home";
            
                array_push( $this->data, $req["vData1"]);
                array_push( $this->data, $req["vData2"]);
                array_push( $this->data, $req["vData3"]);

                unset($req["vModule"]);
                unset($req["vCommand"]);
                unset($req["vData1"]);
                unset($req["vData2"]);
                unset($req["vData3"]);
                
                if($this->module==''){
                    //$this->module  = "xMain";
                    //$this->command = "login";
                    return("");
                }

                $this->parameters = $req;

                if( substr($this->module,0,1) == 'x' ){
                    $moduleFile = PATHVISHNU . "framework/modules/" . $this->module . "/" . $this->module . ".php";
                }
                else{
                    $moduleFile = PATHAPP . "modules/" . $this->module . "/" . $this->module . ".php";
                }

                if(file_exists($moduleFile)){
                    require_once($moduleFile);
                    $xo = new $this->module; 
                    
                    $prResponse = $xo->runPageRequest($this);
                    
                    return($prResponse);    
                }
                else{
                    return(false);
                }
            
            
        }
        
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------


    }


?>