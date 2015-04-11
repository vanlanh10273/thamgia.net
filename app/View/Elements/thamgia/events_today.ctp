<?php
    $city_id = isset($city_id) ? $city_id : DEFAULT_CITY_ID;
    $data = $this->requestAction(array('controller' => 'Events', 'action' => 'getEventsToday', $city_id));
?>

<div class="today">
    <div class="today-title">
        <h2 class="titel">DIỄN RA HÔM NAY</h2>
        <span><?php echo count($data); ?></span>
        <strong class="ico"></strong>
    </div>
    <div class="wd-jcarouselLite-vertical" id="event-today">
        <ul class="new-members scrollbar  ">
            <?php foreach($data as $item){ 
            $url_detail =  $this->Html->url(array('controller' => 'Events', 
                                                        'action'=>'detail',
                                                        'slug'=>Link::seoTitle($item['Event']['title']),
                                                        'id' =>$item['Event']['id']));
            $image_url = $item['Event']['image_thumb_url'] != null ? $item['Event']['image_thumb_url'] : $item['Event']['image_url'];
            
            ?>
            <li>
                <a href="<?php echo $url_detail; ?>">
                    <img src="<?php echo $this->Html->url('/') . $image_url;  ?>" alt="Image"  />
                </a>
                <h3><a href="<?php echo $url_detail; ?>"><?php echo $item['Event']['title']; ?></a></h3>
            </li>
            <?php } ?>            
        </ul>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".today-title").click(function(){
            $(this).toggleClass("active");
            $("#event-today").stop('true','true').slideToggle("slow");
        });
    });
</script>

