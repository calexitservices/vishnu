<?php
    
    $outCode  = "<?php";

    $outCode .= includeDirectory(PATHVISHNU . '\framework\classes');
    $outCode .= includeDirectory(PATHVISHNU . '\framework\classes\codeBuilder');
    $outCode .= includeDirectory(PATHVISHNU . '\framework\includes');
    
    $outCode .= "?>";
    
    $myFile = "../vishnuinclude.php";
    $fh = fopen($myFile, 'w');    
    fwrite($fh, $outCode);
    fclose($fh);    
        
    function includeDirectory($dirname){
        
        $x = "";
        foreach (glob($dirname . "/*.php") as $filename)
        {
            $x .= "include_once $filename";
        }
        
        return($x);
    }
    
    