<?php

    function datafile2Array($fileContent){
        $fileContent = str_replace("\r", "", $fileContent);
        $outArray = array();

        $a = explode("\n", $fileContent);
        
        foreach($a as $row){
            $x = explode(":", $row);
            if( isset($x[0]) && isset($x[1]) ){                   // if key and value set in file
                
                if( isset( $outArray[$x[0]] ) ){                  // multiple entries for same key
                    if( !is_array($outArray[$x[0]]) ){            // change from one element to array
                        $oldVal = $outArray[$x[0]];
                        $outArray[$x[0]] = array();
                        array_push( $outArray[$x[0]], $oldVal );
                        array_push( $outArray[$x[0]], $x[1] );
                    }
                    else{
                        array_push( $outArray[$x[0]], $x[1] );
                    }
                }
                else{
                    $outArray[$x[0]] = $x[1];
                }
            }
        }
        
        return($outArray);
    }

    function array2Datafile($filename, $dataArray){
        
            $fileContent = "";
            if(is_array($dataArray)){
                ksort($dataArray);

                foreach( $dataArray as $k=>$v ){

                    if(is_array($v) ){
                        foreach($v as $av){
                            $fileContent .= $k . ":" . $av . "\n";
                        }
                    }
                    else{
                        $fileContent .= $k . ":" . $v . "\n";
                    }
                }
            }
            
            if($filename){
                file_put_contents($filename, $fileContent);          
            }
            else{
                return($fileContent);
            }
    }

    function arrayReadPath($arr, $path, $defaultValue=false){
        
    $retval = $arr;
 
    $path = explode("/", $path);
 
    for ($x=0; ($x < count($path) and $retval); $x++){
 
        $key = $path[$x];
 
        if (isset($retval[$key])){
            $retval = $retval[$key];
        }        
        //else { $retval = $defaultValue; }
    }
    
    if(is_array($retval)){
        $retval = $defaultValue;
    }
    
    return $retval;        
        
    }
    
    function isJson($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
    
    function getLanguageVar($var, $lang="", $module=""){
        $langfile = "";

        if(strpos($var,".")){
            $varArray = explode(".", $var);
            $module   = $varArray[0];
        }

        if($module==""){
            $langfile = PATHAPP . "app/languages/lang_$lang.php";
        }
        else{
            $langfile = PATHAPP . "modules/$module/languages/lang_$lang.php";
        }
        
        require $langfile;
        
        $r = arrVal($appLang, $var, "langvar?$var");
        return($r);
        
    }
    
    function replaceLanguageVars( $t, $lang){
        $langfile = PATHAPP . "app/languages/lang_$lang.php";
        require $langfile;

        if(is_string($t)){
        
            //preg_match_all("~##([^#]+)##~", $t, $result);
            //preg_match_all("/\[\@([^\]]+)\]/", $t, $result);
            preg_match_all('~\[@(.+?)\]~', $t, $result);    // OR '~\[@([^]]*)\]~'
            
            foreach($result[1] as $v){                
                
                if( substr($v,0,1) != "{" ){  //AVOID JSON RESPONSES TO BE REPLACED DUE TO SQUARE BRACKETS
                    if( strpos($v, ".") > 0 ){
                        $vArray = explode(".", $v);
                        $langMod = $vArray[0];
                        
                        $langModFile = PATHAPP . "modules/$langMod/languages/lang_$lang.php";
                        
                        require_once $langModFile; 
                    }

                    $varVal = "LANGUAGE VAR MISSING: $v - $lang - ";
                    
                    if( isset($appLang[$v]) ){
                        $varVal = $appLang[$v];
                    }
                    
                    $t = str_replace("[@$v]", $varVal, $t);
                }
            }
            
        }
        return($t);
    }

    function debug($debugtext){
        
        if( is_array($debugtext) || is_object($debugtext) ){
            $debugtext = print_r($debugtext, true);
        }
        
        $fp = fopen( PATHAPP . 'debug.txt', 'a');
        fwrite($fp, date("Y-m-d H:i") . "   " . $debugtext . "\n");
        fclose($fp);        
        
    }
    
    function rowToObject($row, $obj){
        
        $clsName = get_class($obj);
        foreach($row as $k=>$v){
            if(property_exists($clsName, $k)){
                $obj->$k = $v;
            }
        }
        
    }
    
    function urlString2Array($inStr){
        $s = explode("&", $inStr);
        $retVal = array();
        
        foreach($s as $item){
            $itemArray = explode("=", $item);
            if(isset($itemArray[0]) and isset($itemArray[1]) ){
                $retVal[ $itemArray[0] ] = $itemArray[1];
            }
        }
        return($retVal);
    }
    
    function array2UrlString($arr){
        $retVal = "";
        foreach($arr as $k=>$v){
            if($retVal) $retVal .= "&";
            $retVal .= "$k=$v";
        }
        return($retVal);
    }
    
    function crypto_rand_secure($min, $max) {
            $range = $max - $min;
            if ($range < 0) return $min; // not so random...
            $log = log($range, 2);
            $bytes = (int) ($log / 8) + 1; // length in bytes
            $bits = (int) $log + 1; // length in bits
            $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
            do {
                $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
                $rnd = $rnd & $filter; // discard irrelevant bits
            } while ($rnd >= $range);
            return $min + $rnd;
    }    
    
    function generateRandomString($length = 15){
        $token = "";
        
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        
        for($i=0;$i<$length;$i++){
            $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
        }
        
        return $token;
    }

    function hex2rgb($hex) {
      $color = STR_REPLACE('#', '', $hex);
      $ret = ARRAY(
       'r' => HEXDEC(SUBSTR($color, 0, 2)),
       'g' => HEXDEC(SUBSTR($color, 2, 2)),
       'b' => HEXDEC(SUBSTR($color, 4, 2))
      );
      RETURN $ret;
    }

    function dirCopy($src,$dst) {
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    dirCopy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }

    function arrVal($arr, $key, $default=""){

        $retVal = $default;

        if( isset($arr[$key]) ){
            $retVal = $arr[$key];
        }

        return($retVal);

    }

    function urlSafe($string){
        $safe = preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $string)));    
        return($safe);
    }

    // LAST REPLACE
    function str_lreplace($search, $replace, $subject) {
        $pos = strrpos($subject, $search);

        if($pos !== false)
        {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    // SUBARRAY SORT
    /*
    function subarray_sort($a,$subkey) {
            $b = array();
            $c = array();
            
            foreach($a as $k=>$v) {
                    $b[$k] = strtolower($v[$subkey]);
            }
            asort($b);
            foreach($b as $key=>$val) {
                    $c[] = $a[$key];
            }
            return $c;
    }
     * 
     */
    
function subarray_sort($array, $value, $asc = true, $preserveKeys = true)
{
    if ($preserveKeys) {
        $c = array();
        if (is_object(reset($array))) {
            foreach ($array as $k => $v) {
                $b[$k] = strtolower($v->$value);
            }
        } else {
            foreach ($array as $k => $v) {
                $b[$k] = strtolower($v[$value]);
            }
        }
        $asc ? asort($b) : arsort($b);
        foreach ($b as $k => $v) {
            $c[$k] = $array[$k];
        }
        $array = $c;
    } else {
        if (is_object(reset($array))) {
            usort($array, function ($a, $b) use ($value, $asc) {
                return $a->{$value} == $b->{$value} ? 0 : ($a->{$value} - $b->{$value}) * ($asc ? 1 : -1);
            });
        } else {
            usort($array, function ($a, $b) use ($value, $asc) {
                return $a[$value] == $b[$value] ? 0 : ($a[$value] - $b[$value]) * ($asc ? 1 : -1);
            });
        }
    }

    return $array;
}    
    

    // DATE FORMATING FUNCTIONS
    define("DATEFORMATEU", 1);
    define("DATEFORMATUS", 2);
    define("DATEFORMATMX", 3);
    define("DATEFORMATSQL", 4);
    
    function reformatDate($dateString, $fromFormat, $toFormat){
        
        // 1 - EU:  d.m.y
        // 2 - US:  m/d/y
        // 3 - MX:  d/m/y
        // 4 - SQL: y-m-d
        
        $df[DATEFORMATEU]  = "dmy.";
        $df[DATEFORMATUS]  = "mdy/";
        $df[DATEFORMATMX]  = "dmy/";
        $df[DATEFORMATSQL] = "ymd-";
        
        $formatFrom = $df[$fromFormat];
        $formatTo   = $df[$toFormat];
        
        $formatArrayFrom = str_split($formatFrom, 1);
        $formatArrayTo   = str_split($formatTo, 1);
        
        // split values of incoming string
        $vals = explode($formatArrayFrom[3], $dateString); // SPLIT BE SEPARATOR OF FORMAT
        
        // reformat string parts
        $vals[ array_search( "d", $formatArrayFrom ) ] = str_pad($vals[ array_search( "d", $formatArrayFrom )], 2, 0, STR_PAD_LEFT);
        $vals[ array_search( "m", $formatArrayFrom ) ] = str_pad($vals[ array_search( "m", $formatArrayFrom )], 2, 0, STR_PAD_LEFT);
        if( strlen( $vals[ array_search( "y", $formatArrayFrom ) ] ) == 2 ){
            $vals[ array_search( "y", $formatArrayFrom ) ] = "20" . $vals[ array_search( "y", $formatArrayFrom ) ];
        }
        
        // build output string
        $outStr = "";
        
        $outStr .= $vals[ array_search( $formatArrayTo[0], $formatArrayFrom ) ];
        $outStr .= $formatArrayTo[3];
        $outStr .= $vals[ array_search( $formatArrayTo[1], $formatArrayFrom ) ];
        $outStr .= $formatArrayTo[3];
        $outStr .= $vals[ array_search( $formatArrayTo[2], $formatArrayFrom ) ];

        return($outStr);
        
    }

    function createDirStructure($dir){

        $dir = str_replace('\\', '/', $dir);
        $dirArray = explode("/", $dir);

        $currentDir = "";

        foreach($dirArray as $v){

            $currentDir .= "$v/";

            if(!file_exists($currentDir) ){
                mkdir($currentDir);
            }
        }

    }

    function numeric($number, $dec = 0){
        
        if(!is_numeric($number)) $number = 0;
        
        
        return( number_format($number,$dec) );
        
    }
    
    function isValidEmail($email){ 
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            
            // no directory found
            //throw new InvalidArgumentException("$dirPath must be a directory");
            
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                //self::deleteDir($file);    changed for online server
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        @rmdir($dirPath);
    }
    
    