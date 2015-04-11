<script type="text/javascript">
    $(document).ready(function() {
        $(".menu ul li").toggleClass('active', false);
        $(".menu ul li.daily-coupon").toggleClass('active');
    });
</script>
<h2 class="titel">
    Sự Kiện Sắp Diễn Ra 
    <?php echo isset($city_name) ? ' - '.$city_name: '';  ?>
    <?php echo '- Daily Coupon';  ?>
</h2>
<ul class="events-new">
    <?php foreach($data as $event){ ?>
    <?php
        $url_detail = $this->Html->url(array(
                                            "controller"=>"Events", 
                                            "action"=> "detail",
                                            'slug' => Link::seoTitle($event['title']),
                                            'id'=> $event['id']));
    ?>
    <li>
        <div class="new-events-img">
            <a href="<?php echo $url_detail ?>">
                <img src="<?php echo $this->Html->url('/') . $event['image_url']; ?>" alt="Image"  />
            </a>
            <p>
                <span><?php echo $event['start_day']; ?></span><span><?php echo $event['start_month'];  ?></span><span class="last"><?php echo $event['start_year']; ?></span>
            </p>
        </div>
        <h3><a href="<?php echo $url_detail; ?>"><?php echo $event['title']; ?></a></h3>
        <p><span><?php echo $event['type'];?></span><a href="#"></a><strong><?php echo $event['likes']; ?></strong><span>Lượt xem</span><strong class="views"><?php echo ($event['views'] + DEFAULT_VIEW); ?></strong></p>
        <p><span><?php echo $event['address']; ?></span></p>
        <?php if ($event['free']){?>
            <span class="free">
            </span>
        <?php }?>
    </li>
    <?php } ?>
    <li class="last">
        <div class="paging">
           <?php
              echo $this->Paginator->prev(__(''), array('tag' => 'span'));
              echo $this->Paginator->numbers(array('separator'=>'<span>-</span>'));      
              echo $this->Paginator->next(__(''), array('tag' => 'span'));
           ?>
        </div>
    </li>
</ul>
