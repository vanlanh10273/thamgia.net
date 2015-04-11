<div class="content-titel">
    <ul class="menu-content">
        <li class="last">
            <a class="active" href="#">Quản lý tham gia</a>
        </li>
    </ul>
</div>
<div class="content-center">
    <div class="personal-information-new">
    </div>
    <div class="personal-information-new-02">
        <div class="wd-section">
            <p class="titel-text">Đã có <?php echo $number_of_participation ?> người tham gia sự kiện <a href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' =>'detail', 'slug' => Link::seoTitle($event_title), 'id' => $event_id)); ?>"><?php echo $event_title; ?></a></p>
            
            <table cellspacing="0" cellpadding="0" border="1" class="table-personal">
                <tbody>
                    <tr class="titel">
                        <td style="width:15%">
                            <p><strong> Thành viên  </strong></p>
                        </td>
                        <td style="width: 15%">
                            <p><strong> Điện thoại </strong></p>
                        </td>
                        <td style="width:35%">
                            <p><strong> Ghi chú </strong></p>
                        </td>
                        <td style="width:15%" >
                            <p><strong> Tham gia lúc </strong></p>
                        </td>
                        <td style="width:15%">
                            <p><strong>Tương tác</strong></p>
                        </td>
                    </tr>
                    <?php foreach($data as $participation){ ?>
                    <tr class="content">
                         <td>
                            <a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'profile', $participation['user_id'])); ?>"><span> <?php echo $participation['user_name']; ?></span></a>
                        </td>
                        <td >
                            <p><span> <?php echo $participation['mobile']; ?></span></p>
                        </td>
                        <td>
                            <p><span> <?php echo $participation['note']; ?></span></p>
                        </td>
                         <td>
                            <p><span> <?php echo $participation['created']; ?> </span></p>
                        </td>
                        <td>
                            <a onclick="sendMessage( <?php echo $participation['user_id']; ?>,'<?php echo $participation['user_name']; ?>')" href="#" class="delete">message</a>
                            <!--<a class="delete" onclick="return confirm('Bạn có chắc là muốn xóa đăng ký sự kiện này?');"  href="<?php echo $this->Html->url(array('controller' => 'Participations', 'action' => 'delete',$participation['user_id'] ,$participation['event_id'])); ?>"><img src="<?php echo $this->Html->url('/')?>images/icn_trash.png" /></a>-->
                        </td>
                    </tr>
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
            <p class="titel-text"><a href="javascript:sendAllMessage();"><img src="<?php echo $this->Html->url('/').'img/send-message.png'; ?>" alt=""/></a></p>    
        </div>
    </div>
</div>
   
<script type="text/javascript">
    function sendMessage(user_id, user_name){
        $("#user_name").text(user_name);       
        $("#receiver").val(user_id);
        $("#click-modal").fancybox({modal:false}).trigger('click');
    }
    
    function sendAllMessage(){
        $("#click-all-message").fancybox({modal:false}).trigger('click');
    }
    
    function validateMessageBox(){
        if ($('#message').val() == ''){
            alert('Vui lòng nhập nội dung tin nhắn!');
            return false;
        }
        return true;
    }
    
    function validateAllMessageBox(){
        if ($('#message-all').val() == ''){
            alert('Vui lòng nhập nội dung tin nhắn!');
            return false;
        }
        return true;
    }
</script>
<div class="fancybox-none">
    <!-- send a message modal   -->
    <a id="click-modal" href="#content-modal"></a>
    <div id="content-modal" class="message-fancybox">
        <div class="select-user">
            <h3>Gởi Tin Nhắn đến <span id="user_name"></span></h3>
        </div>
        <form id="frm_subscription1" name="frm_subscription1" onsubmit="return validateMessageBox();" method="post" action="<?php echo $this->Html->url(array('controller'=>'Messages', 'action' => 'sendMessage')) ?>" class="login-subscription">
            <fieldset>
                <ul>
                    <li>
                        <input id="receiver" name="data[Message][receiver]" type="hidden" />
                        <textarea class="validate[required]" rows="10" cols="50" name="data[Message][content]" id="message"></textarea>
                    </li>
                    <li>
                        <button class="button-1" title="Đăng Sự kiện"  type="submit"><span>Đăng Sự kiện</span></button>
                        <button class="button-2" title="Nhập lại" onclick="$('#fancybox-close').trigger('click'); return false;" type="submit"></button>
                    </li>
                </ul>
            </fieldset>
        </form>        
    </div> 
    
    <!--  send message to all memember modal     -->
    <a id="click-all-message" href="#content-all-message"></a>
    <div id="content-all-message" class="message-fancybox">
        <div class="select-user">
            <h3>Gởi Tin Nhắn đến <span>tất cả người tham gia</span></h3>
        </div>
        <form id="frm_subscription2" name="frm_subscription2" method="post" onsubmit="return validateAllMessageBox();" action="<?php echo $this->Html->url(array('controller'=>'Messages', 'action' => 'sendMessageToAllMember', $event_id)) ?>" class="login-subscription">
            <fieldset>
                <ul>
                    <li>
                        <textarea class="validate[required]" rows="10" cols="50" name="data[Message][content]" id="message-all"></textarea>
                    </li>
                    <li>
                        <button class="button-1" title="Đăng Sự kiện"  type="submit"><span>Đăng Sự kiện</span></button>
                        <button class="button-2" title="Nhập lại" onclick="$('#fancybox-close').trigger('click'); return false;" type="submit"></button>
                    </li>
                </ul>
            </fieldset>
        </form>        
    </div>
</div>

