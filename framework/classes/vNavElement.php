<?php

   class vNavElement{
        /**
         * VISHNU CLASS vNavElement
         * 
         * VISHNU APPLICATION FRAMEWORK CLASS
         * vNavElement
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
        * @var Caption for Navigation Object
        */
        var $caption            = "";

       /**
        * @var Link for navigation element
        */
        var $link               = "";

       /**
        * @var Application Request: Module
        */
        var $module             = "";

       /**
        * @var Application Request: Function
        */
        var $function           = "";

       /**
        * @var Application Request: Data
        */
        var $data                = "";

       /**
        * @var Application Request: Target
        */
        var $target              = "";

       /**
        * @var Limit to users with specific right, do not provide if no limitation required
        */
        var $requiredRight       = "";
        
       /**
        * @var childElements
        */
        var $navElements       = array();
        
        
        
        // ---------------------------------------------------------------------
        // CONSTRUCTOR
        // ---------------------------------------------------------------------
        function __construct(){

        }

        function addNavElement($caption, $link, $requiredRight=""){
            $n = new vNavElement();
            $n->caption = $caption;
            $n->link    = $link;
            $n->requiredRight = $requiredRight;
            array_push($this->navElements, $n);
            
            return($n);
        }
        
        function addNavRequestElement($caption, $target, $module, $function, $data="", $requiredRight=""){
            $n = new vNavElement();
            $n->caption   = $caption;
            $n->target    = $target;
            $n->module    = $module;
            $n->function  = $function;
            $n->data      = $data;
            $n->requiredRight = $requiredRight;
            array_push($this->navElements, $n);
            
            return($n);
        }
        
        
        // ---------------------------------------------------------------------
        // PUBLIC FUNCTIONS
        // ---------------------------------------------------------------------
        
        // ---------------------------------------------------------------------
        // PRIVATE FUNCTIONS
        // ---------------------------------------------------------------------
        
    }

