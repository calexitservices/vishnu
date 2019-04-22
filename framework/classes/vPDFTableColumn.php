<?php

/**
 * Description of vPDFTableColumn
 *
 * @author fr
 */
class vPDFTableColumn {
    
    var $key                 = "";
    var $caption             = "";
    var $align               = "";
    var $style               = "";
    var $width               = 0;
    var $totals              = true;

    function __construct($k, $c, $w, $a="L", $s=null){
        $this->key = $k;
        $this->caption = $c;
        $this->width = $w;
        $this->align = $a;
        $this->style = $s;
    }
    
}

