<?php
    $index = $from;
?>
<?php foreach($data as $event){
    $url_detail = $this->Html->url(array(
                                        "controller"=>"Events", 
                                        "action"=> "detail",
                                        'slug' => Link::seoTitle($event['Event']['title']),
                                        'id'=> $event['Event']['id']));
    $startDate =  new DateTime($event['Event']['start']);
    $image_url = $event['Event']['image_list_url'] != null ? $event['Event']['image_list_url'] : $event['Event']['image_url'];
?>
<li class="item" for="<?php echo $index++; ?>">
    <div class="how-img">
        <a class="img" href="<?php echo $url_detail ?>">
            <img src="<?php echo $this->Html->url('/') . ($image_url != '' ? $image_url : NO_IMG_URL); ?>" alt="Image"  />
        </a>
        <div class="how-none">
            <?php if (!$logged_in){ ?>
                <a href="javascript:<?php   echo 'login()'; ?>" class="dotting"><span>Đánh Dấu</span></a>
                <a href="javascript:<?php   echo 'login()'; ?>" class="thanks"><span>Cảm Ơn</span></a>
            <?php }else{ ?>
                 <?php if ($event['PinsUser']['id'] == null){ ?>
                    <a id="dotting-link-<?php echo $event['Event']['id']; ?>" href="javascript:<?php   echo 'pinsUser('. $event['Event']['id'] . ',' . $users_userid . ')'; ?>" class="dotting"><span>Đánh Dấu</span></a>
                <?php }else{ ?>
                    <a id="dotting-link-<?php echo $event['Event']['id']; ?>" href="javascript:<?php   echo 'removePinsUser('. $event['Event']['id'] . ',' . $users_userid . ')'; ?>" class="dotting dotting-disable"><span>Gỡ Đánh Dấu</span></a>
                <?php } ?>
                
                <?php if ($event['ThanksEvent']['id'] == null){ ?>
                    <a id="thanks-link-<?php echo $event['Event']['id']; ?>" href="javascript:<?php   echo 'thanksEvent('. $event['Event']['id'] . ',' . $users_userid . ')'; ?>" class="thanks"><span>Cảm Ơn</span></a>
                <?php }else{ ?>
                    <a class="thanks"><span>Đã Cám Ơn</span></a>
                <?php } ?>
            <?php } ?>
            <p class="use">Đăng bởi:<a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'profile', $event['Event']['user_id'])); ?>"><?php echo $event['User']['fullname']; ?></a></p>                    
        </div>
    </div>
    <div class="how">
        <h3><a href="<?php echo $url_detail; ?>"><?php echo $event['Event']['title']; ?></a></h3>
        <p><?php echo $event['Event']['address']; ?></p>
        <span><span id="view_<?php echo $event['Event']['id']; ?>">Xem: <?php echo ($event['Event']['views'] + DEFAULT_VIEW) ?></span><span id="thanks_<?php echo $event['Event']['id']; ?>">Cảm ơn: <?php echo $event['Event']['thanks']; ?></span></span>
    </div>
    <a href="#" class="day"><?php echo $startDate->format('d-m-Y'); ?></a>
    <?php if ($event['PinsUser']['id'] != null){ ?>
        <span class="free"> </span>
    <?php } ?>    
</li>
<?php } ?>
