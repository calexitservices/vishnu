<?php

/**
 * Description of vPDFElement
 *
 * @author fr
 */

class vPDFElement {
    var $elementType         = 0;
    var $elementData         = array();
    var $marginTop           = 0;
    var $marginBottom        = 0;
    
    var $elementObject       = null;
    
    var $forceNewPage        = false;
    
    
    function __construct(){
    }
    
    function handleElement($vpdf){
        
        $this->elementObject->handleElement($vpdf);
        
    }
    
    function XXhandleElement($vpdf){
            switch($this->elementType){
                case vPDF::ELTYPETEXT:
                    
                    $elStyle = $vpdf->defaultStyle;

                    if( isset($this->style) ){
                        if( is_object($this->style) ){
                            if( get_class($this->style) == 'vPDFStyle' ){
                                $elStyle = $this->style;
                            }
                        }
                    }
                    
                    $fs = "";
                    if($elStyle->bold) $fs .= "B";
                    if($elStyle->italic) $fs .= "I";
                    if($elStyle->underline) $fs .= "U";

                    $vpdf->pdfObj->SetTextColorArray( $vpdf->pdfColor($elStyle->color) );
                    $vpdf->pdfObj->SetFontSize($elStyle->fontsize);
                    $vpdf->pdfObj->SetFont($elStyle->font, $fs);
                    $vpdf->pdfObj->Write(0, $this->elementData["text"], '', 0, $elStyle->alignment, true, 0, false, false, 0);
                    $vpdf->pdfObj->SetY( $vpdf->pdfObj->GetY() + $elStyle->marginBottom );
                    break;
                case vPDF::ELTYPEHTML:
                    
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
                    
                    $htmlContent = $htmlStyles . $this->elementData["html"];
                    
                    
                    $vpdf->pdfObj->writeHTML($htmlContent, true, false, true, false, '');
                    break;
            }
            
            return($vpdf->pdfObj);
            
      }
    
}



