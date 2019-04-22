<?php


/**
 * Description of vPDFStyle
 *
 * @author fr
 */
class vPDFStyle {
    
    var $font             = "helvetica";
    var $fontsize         = 12;
    var $fill             = false;
    var $bold             = false;
    var $italic           = false;
    var $underline        = false;
    var $color            = array(73,67,66,83);
    var $negativeColor    = array(21,100,99,15);
    var $background       = array(35,28,28,0); 
    var $borderColor      = array(73,67,66,83);
    var $border           = "0";
    var $alignment        = "L";
    var $paddingTop       = 1;
    var $paddingBottom    = 1; 
    var $paddingLeft      = 1; 
    var $paddingRight     = 1; 
    
    
    function setBackground($c1, $c2, $c3, $c4){
        $this->background = array($c1, $c2, $c3, $c4);
    }
    
}

