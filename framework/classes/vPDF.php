<?php

/**
 * Description of vReport
 *
 * @author fr
 */

class vPDF {
    
    var $creator             = "";
    var $author              = "";
    var $title               = "";
    var $subject             = "";
    var $keywords            = "";
    
    var $orientation         = "P";
    var $pagesize            = "letter";
    
    var $marginTop           = 15;
    var $marginLeft          = 20;
    var $marginRight         = 15;
    var $marginBottom        = 15;
    
    var $cssFile             = "";
    var $htmlStyles          = "";
    var $forceDownload       = true;
    
    var $defaultStyle        = null;
    
    var $pdfObj              = null;
    
    /**
     *
     * @var vPDFContainer
     */
    var $container           = null;
    
    // CONSTANTS
    
    const ELTYPETEXT = 1;
    const ELTYPEIMAGE = 2;
    const ELTYPETABLE = 3;
    
    const ELTYPEHTML = 7;
    //const ELTYPEOBJECT = 8;
    const ELTYPECONTAINER = 9;
    
    function __construct(){
        
        $this->container = new vPDFContainer();
        $this->defaultStyle = new vPDFStyle();
        
    }
    
    function addElement($e){
        $re = $this->container->addElement($e);
        return($re);
    }
    
    function createPDF() {
        
        $this->pdfObj = new vTCPDF($this->orientation, "mm", 'letter', true, 'UTF-8', false, false);
        
        $this->pdfObj->SetCreator($this->creator);
        $this->pdfObj->SetAuthor($this->author);
        $this->pdfObj->SetTitle($this->title);
        $this->pdfObj->SetSubject($this->subject);
        $this->pdfObj->SetKeywords($this->keywords);       
        
        $this->pdfObj->bgimage = PATHAPP.'/data/files/pdf/letterheadPortrait.jpg';
        $this->pdfObj->headerHTML = "";
        $this->pdfObj->footerHTML = "";
        
        $this->pdfObj->SetMargins($this->marginLeft,$this->marginTop, $this->marginRight);

        $this->pdfObj->SetAutoPageBreak(true, $this->marginBottom);

        $this->pdfObj->SetFont('helvetica', '', 10);

        $this->pdfObj->AddPage();
        
        // handle elements
        $this->container->handleElement($this);

        //Close and output PDF document
        
        $outType = "I";
        if($this->forceDownload) $outType = "D";
        $this->pdfObj->Output('output.pdf', $outType);        
        
    }
    
    function setDefaultStyle($s){
        $this->defaultStyle = $s;
    }

    /**
     * 
     * @param vPDFStyle $s
     */
    public function pdfColor($color){
        
        if(!is_array( $color ) ) $color = hex2rgb($color);
        return($color);
        
    }
    
}

