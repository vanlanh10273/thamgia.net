
<img src="<?php echo General::getUrlImage($user['users']['avatar_url']); ?>" alt="Image"  />
<div class="how">
    <a class="name" target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'profile', $user['users']['id'])); ?>" class="name"><?php echo $user['users']['fullname']; ?></a>
    <a onclick="return invited(this, <?php echo $event_id; ?>, '<?php echo $user['users']['email']; ?>');" href="#" class="participate"></a>
</div>
<a class="delete" onclick="return deleteInvitation(this);" href="#" class="delete"></a>


