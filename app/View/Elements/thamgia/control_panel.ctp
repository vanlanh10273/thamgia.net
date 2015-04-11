<div class="update-new">
    <h2 class="titel">Bảng điều khiển</h2>
    <ul class="new-control">
        <li>
            <a style="background: #F7F7F7; padding: 0px;" href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "profile", $users_userid)); ?>">
                <img alt="Image" src="<?php echo General::getUrlImage($users_avatar); ?>">
            </a>
            <p class="names"><?php echo $users_name; ?></p>
        </li>
        <li>
            <a class="events" href="<?php echo $this->Html->url(array("controller" => "Events", "action" => "addedEvents")); ?>"><strong>Sự kiện đã đăng</strong></a><p><?php echo $count_added_events; ?></p>
        </li>
        <li>
            <a class="participated-in-events" href="<?php echo $this->Html->url(array("controller" => "Participations", "action" => "participatedEvents")); ?>">Sự kiện đã tham gia</a><p><?php echo $participated_events ?></p>
        </li>
        <li>
            <a class="message" href="<?php echo $this->Html->url(array("controller" => "Messages", "action" => "inbox")); ?>">Tin nhắn</a><p><?php echo $inbox_items; ?></p>
        </li>
        <li>
            <a class="personal-information" href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "profile", $users_userid)); ?>">Thông tin cá nhân</a>
        </li>
        <li>
            <a class="escape" href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "logout")); ?>">Thoát</a>
        </li>
    </ul>
</div>