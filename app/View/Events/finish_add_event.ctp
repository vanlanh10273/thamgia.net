<script type="text/javascript">  
    function leave() {
        window.location = "<?php echo $this->Html->url(array('controller'=>'Events', 'action'=>'addedEvents', $users_userid)); ?>";
    }
    $(document).ready(function() {  
        setTimeout("leave()", 6000);    
    });  
  
</script> 
 
<div class="content-event">
    <div class="login-successful">
        <div class="login-thank">
        <p>Sự kiện của bạn vừa được đăng và đang đợi duyệt nội dung. Bạn muốn nâng cấp sự kiện nổi bật xin vui lòng tham khảo bảng giá <a href="<?php echo $this->Html->Url(array('controller' => 'Home', 'action' => 'advertisement')); ?>">tại đây</a></p>
        </div>
    </div>
</div>