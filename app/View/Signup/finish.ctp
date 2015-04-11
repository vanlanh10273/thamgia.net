<script type="text/javascript">  
    function leave() {
        window.location = "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'login')) ?>";
    }
    $(document).ready(function() {  
        setTimeout("leave()", 5000);    
    });  
  
</script> 
 
<h2 class="titel">ĐĂNG KÝ THÀNH VIÊN</h2>
<div class="registered-users-left">
    <div class="complete-thanks">
        <p><span class="thanks">Cảm ơn bạn đã đăng ký thành viên Mạng sự kiện thamgia.net</span></p>
        <p><span class="info-finish">Thông tin về tài khoản chúng tôi đã gởi về email đăng ký của bạn.</span></p>
        <p><strong>Hệ thống sẽ chuyển về trang chủ sau 5s nữa</strong></p>
    </div>
</div>
<div class="registered-users-right">
    <ul>
        <li class="complete">
            <p><span>1</span>Thông tin tài khoản</p>
            <div class="border-left-01"></div>
        </li>
        <li class="complete">
            <p><span>2</span>Thông tin cá nhân</p>
            <div class="border-left-01"></div>
        </li>
        <li class="complete">
            <p><span>3</span>Hoàn tất</p>
            <div class="border-left-02"></div>
        </li>
        
    </ul>
</div>