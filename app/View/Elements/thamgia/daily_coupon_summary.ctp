
<?php 
    $participations = $this->requestAction(array('controller' => 'ParticipationDailyCoupons', 'action' => 'getParticipations', $daily_coupon_id));
    $thanksDailyCoupon = $this->requestAction(array('controller' => 'ThanksDailyCoupons', 'action' => 'getThanksDailyCoupon', $daily_coupon_id));
    $numberLoop = $totalParticipation/6;
    if ($totalParticipation % 6 == 0){
        $numberLoop -=1;
    }
    $dataVoucher = null;
    $enableVoucher  = $logged_in && $participated;
    if ($enableVoucher){
        $dataVoucher =  $this->requestAction(array('controller' => 'DailyCoupons', 'action' => 'getVoucherInformation', $daily_coupon_id));
    };
?>

<div class="bolck customer-information">
    <h3 class="titel">THÔNG TIN</h3>
    <ul class="customer-information-content">
        <li class="information-01">
            <a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'profile', $owner_id)) ?>"> <img src="<?php echo General::getUrlImage($avatar_url); ?>" alt="Image" title=""  /></a>
            <a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'profile', $owner_id)) ?>"><h4><?php echo $user_name; ?></h4></a>
            <span>Đăng vào: <?php echo $created; ?></span>
        </li>
        <li class="thanks">
            <p><span>Đã có:</span><span onmouseover="bubbleUsersThanksDailyCoupon(this)" id="thanks" class="span-01"><?php echo $thanks; ?> người cảm ơn</span><a href="javascript:<?php   echo $logged_in ? 'thanksDailyCoupon('. $daily_coupon_id . ',' . $users_userid . ')' : 'login()'; ?>">cảm ơn</a></p>
            <div id="thanks_event" style="display: none;">
                    <?php echo $thanksDailyCoupon;?>  
            </div>
        </li>
        
        <li class="times">
            <p>Thời gian còn lại</p>
            <p id="time" class="time-coundown"></p>
        </li>
        <li class="output">
            <p>Đã có<span> <?php echo $totalParticipation; ?></span> người đăng ký tham gia</p>
            <?php if ($totalParticipation > 0){ ?>
            <div class="slides1">
                <div class="s7">
                    <?php for($i=0; $i<=$numberLoop; $i++){ ?>
                    <div>
                        <ul class="join">
                            <?php for($j=0; $j<=5; $j++){ 
                                $index = $i*6 + $j;
                                if ($index >= $totalParticipation)
                                    break;
                            ?>
                            <li><img src="<?php echo General::getUrlImage($participations[$index]['User']['avatar_url']); ?>" alt="Image" title="<?php echo $participations[$index]['User']['fullname']; ?>"  /></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>    
                </div>
                <?php if ($numberLoop > 0){ ?>
                <div class="nav1"><a href="#" class="prev3"></a> <a href="#" class="next3"></a></div>
                <?php } ?>
            </div>
            <?php } ?>
        </li>
        <li class="last">
        <?php if ($eventStatus != STATUS_END){ ?>
            <?php if (!$logged_in){ ?>
                <a class="button-voucher" onclick="login()" href="#"><span>submit</span></a>
            <?php  }else if (!$participated){ ?>
                        <a class="button-voucher" onclick="participate()" href="#"><span>submit</span></a>
            <?php }
            } ?>
        </li>    
    </ul>   
</div>



<div class="fancybox-none">
    <div class="message-fancybox participate-fancybox"  id="modal-participate">
        <h3>Đăng ký tham gia Daily Coupon</h3>        
        <form id="frm_participate" name="frm_participate"  method="post" action="<?php echo $this->Html->url(array("controller" => "ParticipationDailyCoupons", "action" => "participate")); ?>" class="login-subscription">
            <fieldset>
                <ul class="successful">
                    <li>
                        <input type="hidden" id="daily_coupon_id" name="data[ParticipationDailyCoupon][daily_coupon_id]" value="<?php echo $daily_coupon_id; ?>" />
                        <label>Điện thoại</label>
                        <input type="text" name="data[ParticipationDailyCoupon][mobile]" title="" value="<?php echo isset($default_user_mobile) ? $default_user_mobile : ''; ?>" id="phone" class="validate[required] text"/>
                    </li>
                    <li class="general">
                        <label>Ghi chú</label>
                        <textarea id="message" name="data[ParticipationDailyCoupon][note]" cols="30" rows="10"></textarea>
                    </li>
                    <li class="button">
                        <button class="participated" title="Tham Gia" onclick="return submitParticipate(this);" type="submit"><span>Tham Gia</span></button>
                        <div id="loading-particpated" style="display: none;">
                            <img src="<?php echo $this->Html->url('/') . IMG_LOADER;  ?>" alt="" />
                        </div> 
                    </li>
                </ul>
            </fieldset>
        </form>
    </div>
    <a id="click-participate" class="participated"  href="#modal-participate">participate</a>
    
    
</div>           

<script type="text/javascript">
    $(document).ready(function() {
        $("#time").countdown({
            date: '<?php echo $timeCountDown; ?>', //Counting TO a date
            //htmlTemplate: "%{h} <span class=\"cd-time\">hours</span> %{m} <span class=\"cd-time\">mins</span> %{s} <span class=\"cd-time\">sec</span>",
            //date: "july 1, 2011 19:24", //Counting TO a date
            onChange: function( event, timer ){

            },
            onComplete: function( event ){
                $(this).html('<?php echo $eventStatus; ?>');
            },
            leadingZero: true,
            direction: "down"
        });
    });
    
    function participate(){
        $("#click-participate").trigger('click');
        //return false;    
    }
    
    function validateParticipated(){
        if ($('#frm_participate #phone').val() == ''){
            alert('Vui lòng cung cấp số điện thoại!');
            return false;
        }else
            return true;
    }
    
    function submitParticipate(e){
        if (validateParticipated()){
            var event_id = $('#frm_participate #daily_coupon_id').val(); 
            var phone = $('#frm_participate #phone').val();
            var message = $('#frm_participate #message').val();
            
            // hide button participation
            $(e).hide();
            $('#loading-particpated').show();
            
            dataString = 'data[ParticipationDailyCoupon][daily_coupon_id]=' + event_id + '&data[ParticipationDailyCoupon][mobile]=' + phone + '&data[ParticipationDailyCoupon][note]='+message;
            $.ajax({
                url: '<?php echo $this->Html->url(array("controller" => "ParticipationDailyCoupons", "action" => "participate")); ?>',
                type: 'POST',
                data: dataString,
                async: true,
                cache: false,
                timeout: 30000,
                error: function(){
                    alert('Loi tham gia dailycoupon!');
                    $(e).show();
                    $('#loading-particpated').hide();
                    return false;
                },
                success: function(msg){ 
                    alert('Chúc mừng bạn đã đăng ký tham gia sự kiện. Thành viên sở hữu sự kiện này sẽ liên lạc với bạn qua số điện thoại bạn đã gửi đi.');
                    $('#fancybox-close').trigger('click');
                    window.location.reload();     
                }
            });
        };
        return false;
    }
    
    function thanksDailyCoupon(dailyCouponId, userId){
        $.ajax({
           type:"GET",
           async:true,
           url:'<?php echo $this->Html->url(array("controller" => "ThanksDailyCoupons", "action" => "thanksDailyCoupon"))?>' + '/' + dailyCouponId  + '/' + userId,
           dataType: "json",
           success : function(data) {
                if (data.Success){
                    $('#thanks').text(data.Thanks + ' người cám ơn');
                    Cufon.replace('#thanks',{
                        fontFamily: 'UTM NOKIA STANDARD'
                    });
                    
                    $('#thanks_event').append('<?php echo $users_name; ?>' + '<br />');
                }     
           },
           error : function() {
               alert('Lỗi cám ơn sự kiện!');
           },
       });
    }
    
    function bubbleUsersThanksDailyCoupon(e){
        if ($(e).HasBubblePopup())
            $(e).RemoveBubblePopup();
            
        $(e).CreateBubblePopup({

            position : 'top',
            align     : 'center',
            
            innerHtml: $('#thanks_event').html(),            

            innerHtmlStyle: {
                                color:'#FFFFFF', 
                                'text-align':'center'
                            },

            themeName: 'all-azure',
            themePath: '<?php echo $this->Html->url('/'); ?>' + 'img/jquerybubblepopup-themes'
         
        });
    }
</script>

