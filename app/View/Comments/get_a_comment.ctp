<a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' =>'profile', $data['User']['id'])); ?>">
    <img src="<?php echo General::getUrlImage($data['User']['avatar_url']);?>" alt="Image"  />
</a>
<h4><a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' =>'profile', $data['User']['id'])); ?>"><?php echo $data['User']['fullname']; ?></a><span><?php echo date(TIME_FORMAT_CLIENT, strtotime($data['Comment']['created']));?></span></h4>
<p>
    <?php
         echo $data['Comment']['comment'];
     ?> 
</p>                

<a <?php   echo $logged_in ? 'onclick="thanked(this,'.$data['Comment']['id'].');return false;"' : 'onclick="login(); return false;"' ?> class="thank-click" href="#"></a>
<span class="thank"><strong onmouseover="bubbleUsersThanks(this)"><?php echo $data['Comment']['thanks']; ?> người </strong>đã cảm ơn điều này
    <a href="#" <?php   echo $logged_in ? 'onclick="thanked(this,'.$data['Comment']['id'].');return false;"' : 'onclick="login(); return false;"' ?> >cảm ơn</a>
    <div class="users-thanks" style="display: none;">
        <?php echo $data['Comment']['usersThanks'];?>  
    </div>
</span>