<?php

/**
 * Description of vPDFContainer
 *
 * @author fr
 */
class vPDFContainer {
    
    var $elements            = array();
    var $forceNewPage        = false;
    
    /**
     *
     * @var numeric Horizontal position relative to the parent Container.
     */
    var $posX                = 0;
    /**
     *
     * @var numeric Vertical position relative to the parent Container.
     */
    var $posY                = 0;
    
    function __construct(){
        
    }
    
    function addElement($e){
        $el = new vPDFElement();
        $el->elementObject = $e;
        
        array_push($this->elements, $el);
        return($el);
    }
    
    function handleElement($vpdf){

        foreach($this->elements as $el){
            
            if( get_class($el) == "vPDFContainer" ){
                // save x/y coordinates
            }
            
            $el->handleElement($vpdf);
            
            if( get_class($el) == "vPDFContainer" ){
                // set x/y coordinates to previous position
            }
            
            
        }
        
        
    }
    
}

