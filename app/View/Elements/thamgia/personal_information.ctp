<?php 
    $data = $this->requestAction(array('controller' => 'Users', 'action' => 'getUser', $user_id));
?>
<div class="fancybox-none">
    <a id="click-modal" href="#content-modal"></a>
    <div id="content-modal" class="message-fancybox"></div>
</div>

<h2 class="titel">THÔNG TIN CÁ NHÂN</h2>
<div class="personal-information-new-01">
    <img src="<?php echo General::getUrlImage($data['User']['avatar_url']); ?>" alt="Image">
    <h3 class="persona-titel"><?php echo $data['User']['fullname']; ?></h3>
    <ul>
        <li class="top"><p>Đến từ:<span><?php echo $data['City']['name']; ?></span></p></li>
        <li><p>Ngành nghề: <strong><?php echo $data['Career']['name']; ?></strong></p></li>
        <!--<li><p>Email: <a href="#"><?php echo $data['User']['email']; ?></a></p></li>-->
    </ul>
    <a href="javascript:composeMessageBox('<?php echo $data['User']['fullname']; ?>')" onclick="" class="send-message">GỬI TIN NHẮN</a>
    <?php if ($owner){ ?>
    <a href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'editProfile')); ?>" class="editing">CHỈNH SỬA</a>
    <a href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'changePassword')); ?>" class="editing">ĐỔI MẬT KHẨU</a>
    <?php } ?>
</div>



<script type="text/javascript">
    function composeMessageBox(defalutReceiver){
        $.ajax({
           type:"GET",
           url:'<?php echo $this->Html->url(array('controller' => 'Messages', 'action' => 'getComposeMessageBox'))?>/' + defalutReceiver,
           success : function(data) {
                $('#content-modal').empty().html(data);
                $("#click-modal").fancybox({modal:false}).trigger('click');
           },
           error : function() {
               alert('error load compose message box');
           },
        });        
    }
</script>