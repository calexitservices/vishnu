<?php

/**
 * Description of vTCPDF
 *
 * @author fr
 */

require_once( PATHVISHNU . 'resources/tools/tcpdf/tcpdf.php');        


class vTCPDF extends TCPDF {
    
    var $bgimage    = "";
    var $headerHTML = "";
    var $footerHTML = "";
    
    //Page header
    public function Header() {
        
        $oldBreakMargin = $this->getBreakMargin();
        if( file_exists($this->bgimage) ){
            $this->SetAutoPageBreak(false, 0);
            $bgfile = $this->bgimage;
            $this->Image($this->bgimage, 0, 0, $this->w, $this->h, '', '', '', false, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, $oldBreakMargin);
        }
        
        if($this->headerHTML){
            $h = $this->headerHTML;
            $this->writeHTML($h);
        }
        
    }

    // Page footer
    public function Footer() {
        //$this->SetY(-15);
        //$this->SetFont('helvetica', 'I', 8);
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        if($this->footerHTML){
            $h = $this->footerHTML;
            
            $h = str_replace("{page}", $this->getAliasNumPage(), $h);
            $h = str_replace("{pages}", $this->getAliasNbPages(), $h);
            
            $this->writeHTML($h);
        }
        
    }
    

}

