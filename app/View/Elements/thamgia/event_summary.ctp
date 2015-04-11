<script type="text/javascript">
    $(document).ready(function() {
        $("#time").countdown({
            date: '<?php echo $timeCountDown; ?>', //Counting TO a date
            //htmlTemplate: "%{h} <span class=\"cd-time\">hours</span> %{m} <span class=\"cd-time\">mins</span> %{s} <span class=\"cd-time\">sec</span>",
            //date: "july 1, 2011 19:24", //Counting TO a date
            onChange: function( event, timer ){

            },
            onComplete: function( event ){
                <?php if ($eventStatus == STATUS_END) {?>
                    $('#participated').remove();
                <?php } ?>
                $(this).html('<?php echo $eventStatus; ?>');
            },
            leadingZero: true,
            direction: "down"
        });
        
        <?php if ($totalParticipation >0){ ?>
        $('.s7').cycle({ 
                fx:     'fade', 
                speed:  'fast', 
                timeout: 0, 
                next:   '.next3', 
                prev:   '.prev3' 
        });
        <?php } ?>         
    });
    
    function participate(){
        $("#click-participate").trigger('click');
        //return false;    
    }
    
    function validateParticipated(){
        if ($('#phone').val() == ''){
            alert('Vui lòng cung cấp số điện thoại!');
            return false;
        }else
            return true;
    }
    
    function submitParticipate(){
        if (validateParticipated()){
            var event_id = $('#event_id').val(); 
            var phone = $('#phone').val();
            var message = $('#message').val();
            
            dataString = 'data[Participation][event_id]=' + event_id + '&data[Participation][mobile]=' + phone + '&data[Participation][note]='+message;
            $.ajax({
                url: '<?php echo $this->Html->url(array("controller" => "Participations", "action" => "participate")); ?>',
                type: 'POST',
                data: dataString,
                async: true,
                cache: false,
                timeout: 30000,
                error: function(){
                    return true;
                },
                success: function(msg){ 
                    
                }
            });
            alert('Chúc mừng bạn đã đăng ký tham gia sự kiện. Thành viên sở hữu sự kiện này sẽ liên lạc với bạn qua số điện thoại bạn đã gửi đi.');
            $('#fancybox-close').trigger('click');
            window.location.reload();
        };
        return false;
    }
    function thanksEvent(eventId, userId){
        $.ajax({
           type:"GET",
           async:true,
           url:'<?php echo $this->Html->url(array("controller" => "thanksEvents", "action" => "thanksEvent"))?>' + '/' + eventId  + '/' + userId,
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
    
    function bubbleUsersThanksEvent(e){
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
<?php 
    $participations = $this->requestAction(array('controller' => 'Participations', 'action' => 'getParticipationsOfEvents', $event_id));
    $thanksEvent = $this->requestAction(array('controller' => 'ThanksEvents', 'action' => 'getThanksEvent', $event_id));
    $numberLoop = $totalParticipation/6;
    if ($totalParticipation % 6 == 0){
        $numberLoop -=1;
    }
    /*$dataVoucher = null;
    $enableVoucher  = $type_id == TYPE_DAILY_COUPON && $logged_in && $participated;
    if ($enableVoucher){
        $dataVoucher =  $this->requestAction(array('controller' => 'Events', 'action' => 'getVoucherInformation', $event_id));
    }*/
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
            <p><span>Đã có:</span><span onmouseover="bubbleUsersThanksEvent(this)" id="thanks" class="span-01"><?php echo $thanks; ?> người cảm ơn</span><a href="javascript:<?php   echo $logged_in ? 'thanksEvent('. $event_id . ',' . $users_userid . ')' : 'login()'; ?>">cảm ơn</a></p>
            <div id="thanks_event" style="display: none;">
                    <?php echo $thanksEvent;?>  
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
        <?php if (!$participated){ ?>
        <?php if ($is_daily_coupon){ ?>
                <a class="button-voucher" <?php   echo $logged_in ? 'onclick="participate()"' : 'onclick="login()"' ?> href="#"><span>submit</span></a>
            <?php }else{ ?>
                <li class="last">
                    <a class="participation" id="participated" href="javascript:<?php   echo $logged_in ? "participate()" : '"login()"' ?>">tham gia</a>
                </li>
            <?php } ?>
        <li>
            
        </li>
        <?php } /*if ($enableVoucher){ */ ?>
        <!--<li>
            <a class="button-voucher" href="#fancybox-voucher"><span>submit</span></a>
            <div class="fancybox-none">
                <div id="fancybox-voucher" class="voucher">
                    <p><a href="#" class="logo">logo</a></p>
                    <h3><?php echo $dataVoucher['title']; ?></h3>
                    <p class="day">Từ Ngày <span><?php echo $dataVoucher['start'] ?></span> đến ngày <span><?php echo $dataVoucher['end']; ?></span></p>
                    <div class="communication-voucher">
                        <span class="name"><?php echo $users_name; ?></span>
                        <span class="phone"><?php echo isset($default_user_mobile) ? $default_user_mobile : ''; ?></span>
                        <span class="ms"><?php echo $dataVoucher['code']; ?></span>
                    </div>
                    <p>Địa điểm: <?php echo $dataVoucher['address']; ?></p>
                    <p class="note">Vui lòng xuất trình voucher này hoặc mã số tại địa điểm khuyến mãi để được nhận những ưu đãi đặc biệt cho thành viên thamgia.net</p>
                    <p class="file-in">
                    <a class="file" href="<?php echo $this->Html->url(array('action'=>'getPdfVoucher', 'controller'=>'Events', 'ext'=>'pdf', $dataVoucher['id'] )); ?>" target="_blank" ></a>
                    <a  class="in" href="<?php echo $this->Html->url(array('action'=>'printVoucher', 'controller'=>'Events', $dataVoucher['id'] )); ?>" target="_blank"></a>
                    </p>
                    <span class="promotion"><?php echo $data['fee']; ?></span>
                </div>
            </div>
        </li> -->
        <?php /*}*/ ?>
    </ul>   
</div>



<div class="fancybox-none">
    <div class="message-fancybox participate-fancybox"  id="modal-participate">
        <h3>Đăng ký tham gia sự kiện</h3>        
        <form id="frm_participate" name="frm_participate"  method="post" action="<?php echo $this->Html->url(array("controller" => "Participations", "action" => "participate")); ?>" class="login-subscription">
            <fieldset>
                <ul class="successful">
                    <li>
                        <input type="hidden" id="event_id" name="data[Participation][event_id]" value="<?php echo $event_id; ?>" />
                        <label>Điện thoại</label>
                        <input type="text" name="data[Participation][mobile]" title="" value="<?php echo isset($default_user_mobile) ? $default_user_mobile : ''; ?>" id="phone" class="validate[required] text"/>
                    </li>
                    <li class="general">
                        <label>Ghi chú</label>
                        <textarea id="message" name="data[Participation][note]" cols="30" rows="10"></textarea>
                    </li>
                    <li class="button">
                        <button class="participated" title="Tham Gia" onclick="" type="submit"><span>Tham Gia</span></button>
                    </li>
                </ul>
            </fieldset>
        </form>
    </div>
    <a id="click-participate" class="participated"  href="#modal-participate">participate</a>
    
    
</div>

