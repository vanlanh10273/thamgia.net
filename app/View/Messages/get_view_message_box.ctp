<div class="users">
    <div class="users-left">
        <img alt="Image" height="47" width="47" src="<?php echo $this->Html->url('/') . NO_IMG_URL ?>"/>
        <h3><?php echo $data['sender']; ?></h3>
        <p>Gởi đến: <?php echo $data['receiver']; ?></p>
    </div>
    <span class="users-right"><?php echo $data['time_elapse']; ?></span>
</div>
<div class="content-message">
    <p><?php echo $data['content']; ?></p>
</div>
<div class="reply">
    <a href="javascript:composeMessageBox('<?php echo ($data['receiver'] != $users_name ? $data['receiver'] : $data['sender']) ; ?>')" class="reply-message" >reply-message</a>
    <!--<a href="<?php echo $this->Html->url(array('controller' => 'Messages', 'action' => 'delete', $users_userid, $data['id'])); ?>" class="delete-message">delete-message</a>-->
</div>