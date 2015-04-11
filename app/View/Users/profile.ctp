<?php $userInformation = $this->requestAction(array('controller' => 'Users', 'action' => 'getUserInformation', $user_id)); ?>
<div class="content-center">
    <div class="personal-information-new">
        <?php 
            echo $this->element('thamgia/personal_information',
            array(
                'user_id' => $user_id,
                'owner' => $owner
            )); 
        ?>
        
        <div class="personal-information-new-02">
            <div class="border-left"></div>
            <div class="wd-tab">
                <ul class="wd-item">
                    <li><a href="<?php echo $this->Html->url(array("controller" => "Events", "action" => "addedEvents", $user_id)); ?>"><span><?php echo $userInformation['count_added_events']; ?></span>Sự kiện đã đăng</a><span class="border"></span></li>
                    <li><a href="<?php echo $this->Html->url(array("controller" => "Participations", "action" => "participatedEvents", $user_id)); ?>"><span><?php echo $userInformation['count_participated_events']; ?></span>Sự kiện đã tham gia</a><span class="border"></span></li>
                    <?php if ($owner){ ?>
                    <li><a href="<?php echo $this->Html->url(array("controller" => "Messages", "action" => "inbox")); ?>"><span><?php echo $userInformation['count_inbox_items']; ?></span>Tin nhắn</a><span class="border"></span></li>
                    <?php } ?>
                    <li class="log-event"><a href="javascript:addEvent();"><span>90</span>Thêm sự kiện mới</a></li>
                </ul>
            </div>
        </div>
    </div>
    
</div>
