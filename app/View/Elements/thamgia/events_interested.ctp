<?php
    $city_id = isset($city_id) ? $city_id : DEFAULT_CITY_ID;
    $data = $this->requestAction(array('controller' => 'Events', 'action' => 'getEventsInterested', $city_id));
?>
<div class="event-new">
    <h2 class="titel">Sự kiện được quan tâm</h2>
    <ul class="new-content">
        <?php foreach($data as $item){ 
            $url_detail =  $this->Html->url(array('controller' => 'Events', 
                                                        'action'=>'detail',
                                                        'slug'=>Link::seoTitle($item['Event']['title']),
                                                        'id' =>$item['Event']['id']));
        ?>
        <li>
            <a href="<?php echo $url_detail; ?>">
                <img src="<?php echo $this->Html->url('/') . (isset($item['Event']['image_url']) ? $item['Event']['image_url'] : NO_IMG_URL);  ?>" alt="Image"  />
            </a>
            <h3><a href="<?php echo $url_detail;?>"><?php echo $item['Event']['title']; ?></a></h3>
            <p><?php echo $item['Event']['start'] ?></p>
        </li>
        <?php } ?>
    </ul>
</div>
