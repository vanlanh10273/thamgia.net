<?php
    $data = $this->requestAction(array('controller' => 'Users', 'action' => 'getNewMember')); 
?>
<div class="bolck">
    <h2 class="titel">THÀNH VIÊN MỚI</h2>
    <ul class="new-members">
        <?php foreach($data as $member){ 
            $createdDate = new DateTime($member['User']['created']);
        ?>
        <li>
            <a href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "profile", $member['User']['id'] )); ?>">
                <img src="<?php echo General::getUrlImage($member['User']['avatar_url']); ?>" alt="Image" title=""  />
            </a>
            <h3><a href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "profile", $member['User']['id'] )); ?>"><?php echo $member['User']['fullname']; ?></a></h3>
            <span class="day">Tham gia: <?php echo $createdDate->format('d-m-Y') ?></span>
        </li>
        <?php } ?>
        
        <?php if (!$logged_in){ ?>
        <li>
            <a href="<?php echo $this->Html->url(array("controller" => "Signup", "action"=>"account")) ?>" class="registration">đăng ký thành viên mới</a>
        </li>
        <?php } ?>
    </ul>
</div>
                    
