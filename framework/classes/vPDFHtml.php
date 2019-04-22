<?php

/**
 * Description of vPDFText
 *
 * @author fr
 */

class vPDFHtml {
    var $columns                  = array();
    var $style                    = null;
    var $width                    = 0;
    var $html                     = "";
    
    function __construct(){
        $this->style              = new vPDFStyle();
    }
    
    function handleElement($vpdf){
        // handle HTML styles
        $htmlStyles = "";
        if($vpdf->cssFile){
            if(file_exists($vpdf->cssFile) ){
                $htmlStyles .= file_get_contents($vpdf->cssFile);
            }   
        }
        
        if($vpdf->htmlStyles){
            $htmlStyles .= $vpdf->htmlStyles;
        }   

        if($htmlStyles){
            $htmlStyles = "<style>" . $htmlStyles . "</style>";
        }

        $htmlContent = $htmlStyles . $this->html;
        $vpdf->pdfObj->writeHTML($htmlContent, true, false, true, false, '');
    }
    
}



