<?php
    $city_id = isset($city_id) ? $city_id : DEFAULT_CITY_ID;
    $data = $this->requestAction(array('controller' => 'Events', 'action' => 'getEventsOther', $city_id, $type_id, $event_id));
?>
<div class="block-events-04">
    <h2 class="titel">Sự kiện <?php echo $type_name; ?> khác</h2>
    <ul class="new-content">
        <?php
         $index=1;
         foreach($data as $item){
         $url_detail =  $this->Html->url(array('controller' => 'Events', 
                                                        'action'=>'detail',
                                                        'slug'=>Link::seoTitle($item['Event']['title']),
                                                        'id' =>$item['Event']['id'])); 
        ?>
        <li <?php if (($index % 2) ==0 ) echo 'class="right"';  ?> >
            <a href="<?php echo $url_detail; ?>">
                <img src="<?php echo $this->Html->url('/') . (isset($item['Event']['image_url']) ? $item['Event']['image_url'] : NO_IMG_URL); ?>" alt="Image"  />
            </a>
            <h3><a href="<?php echo $url_detail;?>"><?php echo $item['Event']['title']; ?></a></h3>
            <p><?php echo $item['Event']['start']; ?></p>
            <span class="free-1"> </span>
        </li>
        <?php 
            $index++;
        } 
         ?>
    </ul>
</div>
