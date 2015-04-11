<div class="block reported">
    <h3 class="titel">THÔNG TIN</h3>
    <ul class="reported-content">
        <li>
            <p>Gửi sự kiện này đến bạn bè của bạn:</p>
            <a href="#" <?php   echo $logged_in ? 'onclick="return requestYahoo();"' : 'onclick="login(); return false;"' ?>>
                <img src="<?php echo $this->Html->url('/'); ?>img/yahoo.png" alt=""  />   
            </a>
            <a  href="#" <?php   echo $logged_in ? 'onclick="return requestGmail();"' : 'onclick="login(); return false;"' ?>>
                <img src="<?php echo $this->Html->url('/'); ?>img/email.png" alt=""  />   
            </a>
            <a  href="javascript:alert('Xin vui lòng liên hệ 0985 684 772 để được tư vấn thông tin về chức năng SMS Marketing'); return false;"><img src="<?php echo $this->Html->url('/'); ?>img/sms.png" alt="Image"  /></a>
        </li>
        <li>
            <p>Lưu sự kiện vào điện thoại của bạn:</p>
            <a href="http://vi.wikipedia.org/wiki/M%C3%A3_QR" class="qr-code">QR code là gì ?</a>
            <a href=""><img src="<?php echo $this->Html->url('/'); ?>img/phone.png" alt="Image" title=""  /></a>
            <?php echo $this->QrCode->text($event_address . '-' . $event_title); ?>
        </li>
    </ul>
</div>

<script type="text/javascript">
    function requestYahoo(){
        var openWin = window.open('<?php echo $this->Html->url(array('controller' => 'Contacts', 'action' => 'requestYahooContacts', $event_id)); ?>', '_blank', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,resizable=1,width=500,height=500');  
        return false;    
     }
     
     function requestGmail(){
         var openWin = window.open('<?php echo $this->Html->url(array('controller' => 'Contacts', 'action' => 'requestGmailContacts', $event_id)); ?>', '_blank', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,resizable=1,width=500,height=500');  
         return false;
     }
</script>