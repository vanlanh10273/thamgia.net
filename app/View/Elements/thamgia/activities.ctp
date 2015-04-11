<?php
    $maxLength = 35;
    $data = $this->requestAction(array('controller' => 'Activities', 'action' => 'getActivites'));
    if (count($data) > 0){
?>

<div class="today">
    <h2 class="titel">HỌ ĐANG LÀM GÌ</h2>
    <div class="jcarouse" id="activity">
        <ul class="new-members" >
            <?php foreach($data as $activity){
                $url_detail = '';
                $titleOfActivity  = '';
                if (!$activity['Activity']['is_daily_coupon']){
                    $url_detail = $this->Html->url(array('controller' => 'Events', 
                                                            'action'=>'detail',
                                                            'slug'=>Link::seoTitle($activity['Event']['title']),
                                                            'id' =>$activity['Event']['id']));    
                    $titleOfActivity = $activity['Event']['title'];
                }else{
                    $url_detail = $this->Html->url(array('controller' => 'DailyCoupons', 
                                                            'action'=>'detail',
                                                            'slug'=>Link::seoTitle($activity['DailyCoupon']['title']),
                                                            'id' =>$activity['DailyCoupon']['id']));
                    $titleOfActivity = $activity['DailyCoupon']['title'];
                }
                
                // cut title for max length
                if (strlen($titleOfActivity) > $maxLength){
                    $titleOfActivity = implode(' ', array_slice(explode(' ', strip_tags(html_entity_decode($titleOfActivity, ENT_QUOTES, 'UTF-8'))), 0, $maxLength));
                    $titleOfActivity .=  "...";
                    //$titleOfActivity = substr($titleOfActivity, 0, $maxLength) . "...";
                    //$titleOfActivity = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $titleOfActivity);
                }
            ?>
            <li>
                <a target="_blank" class="img" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'profile', $activity['User']['id'])); ?>"><img src="<?php echo General::getUrlImage($activity['User']['avatar_url']); ?>" alt="Image" title=""  /></a>
                <h3>
                    <a  target="_blank" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'profile', $activity['User']['id'])); ?>"><?php echo $activity['User']['fullname']; ?></a>
                    <span class="text"><?php echo $activity['Activity']['description']; ?></span>
                    <a target="_blank" href="<?php echo $url_detail; ?>"><?php echo $titleOfActivity; ?></a>
                </h3>
                <?php if ($activity['Activity']['city_id'] != 0){ ?>
                <p>tại <a href="<?php echo $this->Html->url(array('controller' => 'Home', 'action' => 'city','slug' => Link::seoTitle($activity['City']['name']),'city_id' => $activity['City']['id']))?>"> <?php echo $activity['City']['name'] ?></a> cách đây <?php echo TimeFormat::TimeToString(time()-strtotime($activity['Activity']['created'])); ?> </p>
                <?php }else{  ?>
                <p>cách đây <?php echo TimeFormat::TimeToString(time()-strtotime($activity['Activity']['created'])); ?> </p>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
       $("#activity").jCarouselLite({  
            vertical: true,                
            hoverPause:true,            
            visible: 4,                    
            auto:1000,                    
            speed:2000                    
       });
    });
</script>
<?php } ?>