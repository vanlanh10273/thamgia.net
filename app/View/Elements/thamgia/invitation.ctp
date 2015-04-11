<?php 
    $users = $this->requestAction(array('controller' => 'Invitations', 'action' => 'getMaxUsers'));
?>
<div class="block invited-participate">
    <h3 class="titel">Mời tham gia</h3>
    <ul id="invited">
        <?php foreach($users as $user){ ?>
        <li>
            <img src="<?php echo General::getUrlImage($user['users']['avatar_url']); ?>" alt="Image"  />
            <div class="how">
                <a class="name" target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'profile', $user['users']['id'])); ?>" class="name"><?php echo $user['users']['fullname']; ?></a>
                <a class="participate" <?php   echo !$logged_in ? "onclick=\"login(); return false;\"" : "onclick=\"return invited(this,".$event_id .",'". $user['users']['email']."');\"";?> href="#" class="participate"></a>
            </div>
            <a class="delete" onclick="return deleteInvitation(this);" href="#" class="delete"></a>
        </li>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript">
    function deleteInvitation(e){
         $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'Invitations', 'action' => 'getInvitation', $event_id)); ?>',
            type: 'GET',
            async: false,
            error: function(){
                $(e).parent().fadeOut(500, function(){
                    $(e).parent().css('display','block');
                });
                /*alert('Error load invitation!')*/
            },
            success: function(data){ 
                $(e).parent().fadeOut(500, function(){
                    $(e).parent().css('display','block');
                    $(e).parent().empty().append(data);
                });
            }
        });
        
        return false;
    }
    
    function invited(e, event_id, email){
        // post send contact
        $.post("<?php echo $this->Html->url(array('controller' => 'Invitations','action' => 'sendEmailInvitation')) ?>", {event_id: event_id, email: email});
            
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'Invitations', 'action' => 'getInvitation', $event_id)); ?>',
            type: 'GET',
            async: false,
            error: function(){
                alert('Error load invitation!')
            },
            success: function(data){ 
                $(e).parent().parent().fadeOut(500, function(){
                    $(e).parent().parent().css('display','block');
                    $(e).parent().parent().empty().append(data);
                });
            }
        });
        return false;
    }
</script>
                        