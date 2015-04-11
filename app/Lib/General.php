<?php
    class General{
        static function json_pretty_print($json, $html_output=false) {
            $spacer = '  ';
            $level = 1;
            $indent = 0; // current indentation level
            $pretty_json = '';
            $in_string = false;

            $len = strlen($json);

            for ($c = 0; $c < $len; $c++) {
                $char = $json[$c];
                switch ($char) {
                    case '{':
                    case '[':
                        if (!$in_string) {
                            $indent += $level;
                            $pretty_json .= $char . "\n" . str_repeat($spacer, $indent);
                        } else {
                            $pretty_json .= $char;
                        }
                        break;
                    case '}':
                    case ']':
                        if (!$in_string) {
                            $indent -= $level;
                            $pretty_json .= "\n" . str_repeat($spacer, $indent) . $char;
                        } else {
                            $pretty_json .= $char;
                        }
                        break;
                    case ',':
                        if (!$in_string) {
                            $pretty_json .= ",\n" . str_repeat($spacer, $indent);
                        } else {
                            $pretty_json .= $char;
                        }
                        break;
                    case ':':
                        if (!$in_string) {
                            $pretty_json .= ": ";
                        } else {
                            $pretty_json .= $char;
                        }
                        break;
                    case '"':
                        if ($c > 0 && $json[$c - 1] != '\\') {
                            $in_string = !$in_string;
                        }
                    default:
                        $pretty_json .= $char;
                        break;
                }
            }

            return ($html_output) ?
                    '<pre>' . htmlentities($pretty_json) . '</pre>' :
                    $pretty_json . "\n";
        } 
        
        static function timeToString($timeline) {
            $periods = array('ngày' => 86400, 'giờ' => 3600, 'phút' => 60, 'giây' => 1);
            $ret = '';
            foreach($periods AS $name => $seconds){
                $num = floor($timeline / $seconds);
                $timeline -= ($num * $seconds);
                if ($num > 0){
                    $ret = $num.' '.$name.' ';
                    break;   
                }
            }

            return trim($ret);
        } 
        
        static function getTimeElapse($time){
            return General::timeToString(time() - strtotime($time)). ' trước';
        }
        
        static function getSummary($description){
            $breakPage = '<div style="page-break-after: always;">';
            $description = trim($description);
            $pos = strpos($description, $breakPage);
            return substr($description, 0, $pos);
        }
        
        static function generateVoucherCode(){
            $code = strtotime("now");
            $code = str_replace(".", "", $code);
            $code = substr($code, strlen($code) - 5, 5);    
            return $code;
        }
        
        static function getUrlImage($url){
            $urlImage = ''; 
            if (false === strpos($url, '://')) {
                $urlImage = BASE_URL . ($url != '' ? $url : NO_IMG_URL );
            }else
                $urlImage = $url;
            return $urlImage;
        }
    }
?>
