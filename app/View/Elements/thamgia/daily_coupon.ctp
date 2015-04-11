<?php 
$city_id = isset($city_id) ? $city_id : DEFAULT_CITY_ID;
$data = $this->requestAction(array('controller' => 'DailyCoupons', 'action' => 'getDailyCoupon', $city_id));
if (count($data) > 0){ ?>

<?php
    
    
    $numberItem = 3;
    $numberLoop = (int)(count($data)/$numberItem);
    if ((count($data) % $numberItem) != 0)
        $numberLoop +=1;
    
?>

<div id="daily-coupon">
    <div class="center">
        <h2 class="daily-coupon-titel">Daily Coupon</h2>
        <a href="javascript:dailyCoupon();" class="daily-coupon-posted">Đăng Daily Coupon</a>
        <div class="slides2">
            <div class="s8">
                    <?php 
                        for($i=0; $i<$numberLoop; $i++){
                    ?>
                    <div>
                        <ul class="join">
                            <?php for($j=0; $j<$numberItem; $j++){
                                $index =  $i*$numberItem + $j;
                                if ($index > count($data) -1)
                                    break;
                                $event_url = $this->Html->url(array('controller' => 'DailyCoupons', 
                                                                    'action' => 'detail',
                                                                    'slug' => Link::seoTitle($data[$index]['DailyCoupon']['title']),
                                                                    'id' => $data[$index]['DailyCoupon']['id']));
                             ?>    
                            <li <?php echo ( $j==0?  "class='top'" : ($j==$numberItem-1 ? "class='last'" : "")); ?> >
                                <a href="<?php echo $event_url; ?>" class="img">
                                    <img src="<?php echo $this->Html->url('/') . (isset($data[$index]['DailyCoupon']['image_thumb_url']) ? $data[$index]['DailyCoupon']['image_thumb_url'] : NO_IMG_URL);  ?>" alt="Image" title=""  />
                                </a>
                                <h3 class="titel"><a href="<?php echo $event_url; ?>"><?php echo $data[$index]['DailyCoupon']['title']; ?></a></h3>
                                <span class="day"><?php echo $data[$index]['DailyCoupon']['end']; ?></span>
                                <a href="<?php echo $event_url; ?>" class="join"></a>    
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
            </div>   
            <div class="nav2"><a href="#" class="prev4"></a> <a href="#" class="next4"></a></div>
        </div>        
    </div>
</div>

<div class="fancybox-none">
    <div class="message-fancybox"  id="modal-daily-coupon">
        <h3>Đăng ký daily coupon</h3>        
        <form id="frm_daily_coupon" name="frm_daily_coupon"  method="post" action="<?php echo $this->Html->url(array("controller" => "Events", "action" => "postDailyCoupon")); ?>" class="login-subscription">
            <fieldset>
                <ul class="successful">
                    <li>
                        <label>Tên doanh nghiệp</label>
                        <input type="text" name="data[DailyCoupon][group]" title="" value="" id="phone" class="validate[required] text"/>
                    </li>
                    <li class="general">
                        <label>Địa chỉ</label>
                        <input id="address" style="width: 260px; background-position: -527px -83px;" name="data[DailyCoupon][address]" value="" class="text"></input>
                        <div class="select-1">
                            <select name="data[DailyCoupon][city]" id="city_id" style="z-index: 10; opacity: 0;">
                                <option value="Hà Nội">Hà Nội</option>
                                <option value="Đà Nẵng">Đà Nẵng</option>
                                <option value="Hồ Chí Minh">Hồ Chí Minh</option>
                            </select>
                        </div>
                    </li>
                    <li class="general">
                        <label>Người liên hệ</label>
                        <input id="contact_name" name="data[DailyCoupon][user_name]" value="" class="validate[required] text"></input>
                    </li>
                    <li class="general">
                        <label>Điện thoại</label>
                        <input id="moblie" name="data[DailyCoupon][mobile]" class="text"></input>
                    </li>
                    <li class="general">
                        <label>Email</label>
                        <input id="email" name="data[DailyCoupon][email]" value="<?php echo $logged_in ? $users_email : ""; ?>" class="validate[required,custom[email]] text"></input>
                    </li>
                    <li class="general">
                        <label style="width: 200px;">Mô tả về sản phẩm, dịch vụ</label>
                        <textarea id="content" name="data[DailyCoupon][content]" cols="20" rows="10"></textarea>
                    </li>
                    <li class="button">
                        <button class="button-participated" onclick="submitPostDailyCoupon();" title="Tham Gia"  type="button"><span>Tham Gia</span></button>
                    </li>
                </ul>
            </fieldset>
        </form>
    </div>
    <a id="click-daily-coupon" class="participated"  href="#modal-daily-coupon">participate</a> 
</div>

<script type="text/javascript">
    $(document).ready(function() {
       $('.s8').cycle({ 
            fx:     'fade',
            speed:   300, 
            timeout: 3000, 
            next:   '.next4', 
            prev:   '.prev4' 
        });
        $("#frm_daily_coupon").validationEngine();
        $("#fancybox-close").bind('click',function(e){
            $("#frm_daily_coupon").validationEngine("hide");
        });
        // active hover to menu daily-coupon
       /* $(".menu ul li").toggleClass('active', false);
        $(".menu ul li.daily-coupon").toggleClass('active');   */ 
    });
    function dailyCoupon(){
        $("#click-daily-coupon").trigger('click');
        //return false;    
    }
    
    function submitPostDailyCoupon(){
        if ($("#frm_daily_coupon").validationEngine('validate')){
            $.ajax({
               type: "POST",
               url: '<?php echo $this->Html->url(array("controller" => "Events", "action" => "postDailyCoupon")); ?>',
               data: $("#frm_daily_coupon").serialize(), // serializes the form's elements.
               success: function(data){
                    alert("Cảm ơn anh/chị đã gửi yêu cầu tham gia chương trình Daily Coupon. Đội ngũ nhân viên thamgia.net sẽ liên hệ với anh/chị để hoàn tất các thủ tục tham gia chương trình. Trân trọng!");
               }
            });
            $("#fancybox-close").trigger('click');    
        }
        return false;
    }
    
    /*function validateCaptcha(captcha){       
        var data = {'captcha_code': captcha};
        var result = false;
        $.ajax({
           type: "GET",
           url: '<?php echo $this->Html->url(array("controller" => "Signup", "action" => "validateCaptcha")); ?>',
           data: data,
           success: function(success){
               result = success;
               return result;
           }
        });    
    }*/
</script>

<?php } ?>