<?php

/**
 * Description of vEmail
 *
 * @author fr
 */
class vEmail {
    
    var $to                  = "";
    var $cc                  = "";
    var $bcc                 = "";
    
    var $subject             = "";
    var $html                = "";
    var $plain               = "";
    
    var $senderName          = "";
    var $senderEmail         = "";

    var $email               = null;
    
    function __construct(){
        require_once PATHVISHNU . 'resources/tools/phpmailer/PHPMailerAutoload.php';
        
        $this->email = new PHPMailer();
    }
    
    function send(){

        $this->email->isSendmail();

        $this->email->setFrom($this->senderEmail, $this->senderName);
        //$mail->addReplyTo('replyto@example.com', 'First Last');
        $this->email->addAddress($this->to);
        if($this->cc)  $this->email->addCC($this->cc);
        if($this->bcc) $this->email->addBCC($this->bcc);
        
        $this->email->Subject = $this->subject;
        if($this->html)  $this->email->msgHTML($this->html);
        if($this->plain) $this->email->AltBody = $this->plain;
        
        return( $this->email->send() );
        
    }
    
}

