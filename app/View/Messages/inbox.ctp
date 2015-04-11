
<script type="text/javascript">
    function viewMessageBox(messageId){
        $.ajax({
           type:"GET",
           url:'<?php echo $this->Html->url(array('controller' => 'Messages', 'action' => 'getViewMessageBox'))?>/' + messageId,
           success : function(data) {
                $('#content-modal').empty().html(data);
                $("#click-modal").fancybox({modal:false}).trigger('click');
           },
           error : function() {
               alert('error load view message box');
           },
        });   
    }
    
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
    $(document).ready(function() {
        $('.message-box').bind('click', function() {
            
        }); 
        
    })    
</script>
<div class="fancybox-none">
    <a id="click-modal" href="#content-modal"></a>
    <div id="content-modal" class="message-fancybox"></div>
</div>

<div class="content-event">
    <div class="content-titel">
        <ul class="menu-content">
            <li class="last">
                <a class="active" href="#">tin nhắn</a>
            </li>
        </ul>
    </div>
    <div class="mailbox">
        <ul>
            <li class="message-mailbox"><p>Hộp thư đến (<span><?php echo $inbox_items; ?></span>)</p></li>
            <li><a href="<?php echo $this->Html->url(array('controller' => 'Messages', 'action' => 'sendItems')) ?>"><strong>Hộp thư đã gủi</strong></a></li>
        </ul>
        <button class="button-create" onclick="composeMessageBox('')" title="Đăng Sự kiện" type="submit"><span>Tạo mới</span></button>
    </div>
    <div class="management-message">
        <table cellspacing="0" cellpadding="0" border="1">
            <tbody>
                <tr class="titel">
                    <td class="members-involved">
                        <p>Người gởi</p>
                    </td>
                    <td class="logged-involved">
                        <p>Nội Dung</p>
                    </td>
                    <td class="interactive">
                        <p>Xóa</p>
                    </td>
                </tr>
                <?php foreach($data as $message){ ?>
                <tr class="content">
                    <td class="members">
                        <p><?php echo $message['sender']; ?></p>
                    </td>
                    <td class="update">
                        <a class="message-box <?php echo $message['viewed']; ?>" href="javascript:viewMessageBox(<?php echo $message['id']; ?>)"><?php echo $message['content']; ?> (<?php echo $message['time_elapse']; ?>)</span></a>
                    </td>
                    <td class="interactive">
                        <a class="delete"  onclick="return confirm('Bạn có muốn xóa tin nhắn này không?');"  href="<?php echo $this->Html->url(array('controller' => 'Messages', 'action' => 'delete', $users_userid, $message['id'], 0)); ?>">delete</a> 
                    </td>
                <?php } ?>
            </tbody>
        </table>
        <div class="paging">
        <?php
          echo $this->Paginator->prev(__(''), array('tag' => 'span'));
          echo $this->Paginator->numbers(array('separator'=>'<span>-</span>'));      
          echo $this->Paginator->next(__(''), array('tag' => 'span'));
        ?>
        </div>
    </div>
</div>