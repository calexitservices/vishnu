<?php

/**
 * Description of vPDFElement
 *
 * @author fr
 */

class vPDFTable {
    var $columns                  = array();
    private $data                 = array();
    
    private $styleHeader          = null;
    private $styleData            = null;
    private $styleAlternate       = null;
    private $styleTotals          = null;
    
    var $totals                   = false;
    
    function __construct(){
        $this->styleHeader        = new vPDFStyle();
        $this->styleData          = new vPDFStyle();
        $this->styleAlternate     = new vPDFStyle();
        $this->styleTotals        = new vPDFStyle();
    }

    function addColumn($key, $caption, $width, $align="L", $style=null){
        $c = new vPDFTableColumn($key, $caption, $width, $align, $style);
        $this->columns[$key] = $c;
    }
    
    function setStyleHeader($s){
        $this->styleHeader = $s;
    }
    function setStyleData($s){
        $this->styleData = $s;
    }
    function setStyleAlternate($s){
        $this->styleAlternate = $s;
    }
    function setStyleTotals($s){
        $this->styleTotals = $s;
    }
    
    function setData($d){
        $this->data = $d;
    }
    
    function handleElement($vpdf){
        
        $vpdf->pdfObj->SetFillColorArray($this->styleHeader->background);
        $vpdf->pdfObj->SetDrawColorArray($this->styleHeader->borderColor);
        $vpdf->pdfObj->SetTextColorArray($this->styleHeader->color);
        
        // header output
        foreach($this->columns as $colKey=>$col){
            $vpdf->pdfObj->cell(30, 0, $col->caption, $this->styleHeader->border, 0, $col->align, $this->styleHeader->fill);
        }
        $vpdf->pdfObj->ln();
        
        // data output
        
        
        $rowcount = 0;
            
        foreach($this->data as $row){
            $rowcount++;
            
            if(is_object($this->styleAlternate) and $rowcount % 2 == 0 ){
                $vpdf->pdfObj->setTextColorArray($this->styleAlternate->color);
                $vpdf->pdfObj->SetFillColorArray($this->styleAlternate->background);
                $vpdf->pdfObj->SetDrawColorArray($this->styleAlternate->borderColor);
                $fillCell   = $this->styleAlternate->fill;
                $borderCell = $this->styleAlternate->border;
            }
            else{
                $vpdf->pdfObj->setTextColorArray($this->styleData->color);
                $vpdf->pdfObj->SetFillColorArray($this->styleData->background);
                $vpdf->pdfObj->SetDrawColorArray($this->styleData->borderColor);
                $fillCell   = $this->styleData->fill;
                $borderCell = $this->styleData->border;
            }
            
            foreach($row as $colKey=>$col){
                $vpdf->pdfObj->cell(30, 0,$col, $borderCell, 0, $this->columns[$colKey]->align, $fillCell);
            }
            $vpdf->pdfObj->ln();
            
        }
        $vpdf->pdfObj->ln();
        
        return($vpdf);
            
      }
    
}

