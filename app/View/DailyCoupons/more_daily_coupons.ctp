<?php
    $index = $from;
?>
<?php foreach($data as $daily_coupon){
    $url_detail = $this->Html->url(array(
                                        "controller"=>"DailyCoupons", 
                                        "action"=> "detail",
                                        'slug' => Link::seoTitle($daily_coupon['DailyCoupon']['title']),
                                        'id'=> $daily_coupon['DailyCoupon']['id']));
    $startDate =  new DateTime($daily_coupon['DailyCoupon']['start']);
?>
<li class="item" for="<?php echo $index++; ?>">
    <span class="promotion-01"><?php echo $daily_coupon['DailyCoupon']['discount'];?></span>
    <div class="how-img">
        <a class="img" href="<?php echo $url_detail ?>">
            <img src="<?php echo $this->Html->url('/') . ($daily_coupon['DailyCoupon']['image_url'] != '' ? $daily_coupon['DailyCoupon']['image_url'] : NO_IMG_URL); ?>" alt="Image"  />
        </a>
    </div>
    <div class="how">
        <h3><a href="<?php echo $url_detail; ?>"><?php echo $daily_coupon['DailyCoupon']['title']; ?></a></h3>
        <p><?php echo $daily_coupon['DailyCoupon']['address']; ?></p>
        <span><span id="view_<?php echo $daily_coupon['DailyCoupon']['id']; ?>">Xem: <?php echo ($daily_coupon['DailyCoupon']['views'] + DEFAULT_VIEW) ?></span><span id="thanks_<?php echo $daily_coupon['DailyCoupon']['id']; ?>">Cảm ơn: <?php echo $daily_coupon['DailyCoupon']['thanks']; ?></span></span>
    </div>
    <a href="#" class="day"><?php echo $startDate->format('d-m-Y'); ?></a>
</li>
<?php } ?>
