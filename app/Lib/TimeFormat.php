<?php
    class TimeFormat{
        static function ClientFormat($date_time){
            $date_time_obj = new DateTime($date_time);
            return $date_time_obj->format(TIME_FORMAT_CLIENT);
        }
        
        static function TimeToString($timeline) {
                $periods = array('ngày' => 86400, 'giờ' => 3600, 'phút' => 60, 'giây' => 1);
                $ret = '';
                foreach($periods AS $name => $seconds){
                    $num = floor($timeline / $seconds);
                    $timeline -= ($num * $seconds);
                    if ($num > 0){
                        $ret = $num.' '.$name.(($num > 1) ? '' : '').' ';
                        break;   
                    }
                }

                return trim($ret);
          }
    }  
?>
