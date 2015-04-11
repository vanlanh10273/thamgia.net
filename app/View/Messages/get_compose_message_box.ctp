<?php
     echo $this->Html->css('jquery.autocomplete');
     echo $this->Html->script('jquery.autocomplete.js');
?>

<div class="select-user">
    <h3>Gởi Tin Nhắn đến:</h3>
    <?php 
            echo $this->Ajax->autoComplete('user.fullname', Router::url(array('controller' => 'Users', 'action' => 'search'), true), array('id'=>'receiver-select', 'label'=>'', 'value'=> $defaultReceiver)); 
    ?>
    
</div>
<form id="frm_subscription1" name="frm_subscription1" method="post" onsubmit="return validateMessage();" action="<?php echo $this->Html->url(array('controller'=>'Messages', 'action' => 'sendMessage')) ?>" class="login-subscription">
    <fieldset>
        <ul>
            <li>
                <input id="receiver" name="data[Message][receiver]" type="hidden" />
                <textarea class="validate[required]" rows="10" cols="50" name="data[Message][content]" id="message"></textarea>
            </li>
            <li id="action-section">
                <button  class="button-1" title="Đăng Sự kiện" type="submit"><span>Đăng Sự kiện</span></button>
                <button class="button-2" title="Nhập lại" onclick="$('#fancybox-close').trigger('click'); return false;" type="submit"></button>
            </li>
            <li>
                <img id="loading-send-message" src="<?php echo $this->Html->url('/') . IMG_LOADER;  ?>" alt="" />
            </li>
        </ul>
    </fieldset>
</form>

<script type="text/javascript">
    function validateMessage(){
        var validate = true;
        showLoading();
        if ($('#receiver-select').val() != ''){
            $.ajax({
               type:"GET",
               async:false,
               url:'<?php echo $this->Html->url(array('controller' => 'Messages', 'action' => 'checkUserName'))?>/' + $('#receiver-select').val(),
               success : function(data) {
                    if (data == 0){
                        alert('Vui lòng nhập lại tên người nhận tin nhắn!');
                        validate = false;
                    }else{
                        $('#receiver').val(data);
                        // check content of message
                        if ($('#message').val() == ''){
                            alert('Vui lòng nhập nội dung tin nhắn!');
                            validate = false;
                        }
                    }
               },
               error : function() {
                   alert('Lỗi load hộp soạn tin nhắn!');
                   validate = false;
               },
            });   
        }else{
            alert('Vui lòng nhập tên người nhận tin nhắn!');
            validate = false;    
        }
        
        if (!validate)
            hideLoading();
        return validate;
    }
    
    function showLoading(){
        $('#loading-send-message').css('display','block');
        $('#action-section').css('display', 'none');
    }
    
    function hideLoading(){
        $('#loading-send-message').css('display','none');
        $('#action-section').css('display', 'block');
    }
</script>