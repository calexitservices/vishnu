<?php

    includeDirectory(PATHVISHNU . '/framework/classes');
    includeDirectory(PATHVISHNU . '/framework/classes/codeBuilder');
    includeDirectory(PATHVISHNU . '/framework/includes');
    includeDirectory(PATHAPP    . '/app/classes');
    
    //CHECK SESSION
    
    function includeDirectory($dirname){

        foreach (glob($dirname . "/*.php") as $filename)
        {
            include_once $filename;
        }
        
    }
        
