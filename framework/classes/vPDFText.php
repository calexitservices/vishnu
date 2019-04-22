<?php

/**
 * Description of vPDFText
 *
 * @author fr
 */

class vPDFText {
    var $columns                  = array();
    var $style                    = null;
    var $width                    = 0;
    var $text                     = "";
    
    function __construct(){
        $this->style              = new vPDFStyle();
    }
    
    function handleElement($vpdf){

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
                    $vpdf->pdfObj->Write(0, $this->text, '', 0, $elStyle->alignment, true, 0, false, false, 0);
                    //$vpdf->pdfObj->SetY( $vpdf->pdfObj->GetY() + $elStyle->marginBottom );
        
      }
    
}

