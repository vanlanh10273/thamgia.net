<?php
class HighlightsController extends AppController{
    function getHighlights(){
        $this->autoRender = false;
        $numberHigliht = 5;
        $current = new DateTime();

        $city_id = DEFAULT_CITY_ID;
        if ($this->Session->read(CITY_ID) != null)
                $city_id = $this->Session->read(CITY_ID);
                
        $options['conditions'] = array(
                'Highlight.city_id =' => $city_id,
                'Highlight.start <=' => $current->format(TIME_FORMAT_MYSQL),
                'Highlight.end >=' => $current->format(TIME_FORMAT_MYSQL)
        );    
        $highlight = $this->Highlight->find('all', $options);
        $countItem = count($highlight);
        $data = array();
        if ($countItem > $numberHigliht){
            $arrKey = array_rand($highlight, $numberHigliht);
            $index=0;
            foreach($arrKey as $key){
                $data[$index] = $highlight[$key];
                $index++;
            }
        }else if ($countItem < $numberHigliht){
            $data = $highlight;
            $addItems = $numberHigliht - $countItem;
            for($i=0; $i<$addItems; $i++){
                $data[$countItem+$i] = array('Highlight' => array(
                                                                'title' => '',
                                                                'start_event' => '',
                                                                'image_url' => HIGHLIGHT_NO_IMG_URL,
                                                                'event_url' => '#',
                                                                'free' => false
                                                            ));
            }
        }else{
            $data = $highlight;
        }

        /*$index = 0;
        foreach($data as $item){
            if ($item['Highlight']['start_event'] != ''){
                $startObj = new DateTime($item['Highlight']['start_event']);
                $data[$index]['Highlight']['start_event'] = $this->dateToVieFormat($startObj);
            }
            $index++;
        }*/
        return $data;
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">

<html>
<head>
  <meta name="generator" content=
  "HTML Tidy for Windows (vers 14 February 2006), see www.w3.org">

  <title></title>
</head>

<body>
</body>
</html>
