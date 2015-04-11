<?php 
    $userInformation = $this->requestAction(array('controller' => 'Users', 'action' => 'getUserInformation', $user_id)); 
?>
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
                    <li class="active"><a href="<?php echo $this->Html->url(array("controller" => "Events", "action" => "addedEvents", $user_id)); ?>"><span><?php echo $userInformation['count_added_events']; ?></span>Sự kiện đã đăng</a><span class="border"></span></li>
                    <li><a href="<?php echo $this->Html->url(array("controller" => "Participations", "action" => "participatedEvents", $user_id)); ?>"><span><?php echo $userInformation['count_participated_events']; ?></span>Sự kiện đã tham gia</a><span class="border"></span></li>
                    <?php if ($owner){ ?>
                    <li><a href="<?php echo $this->Html->url(array("controller" => "Messages", "action" => "inbox")); ?>"><span><?php echo $userInformation['count_inbox_items']; ?></span>Tin nhắn</a><span class="border"></span></li>
                    <?php } ?>
                    <li class="log-event"><a href="javascript:addEvent();"><span>90</span>Thêm sự kiện mới</a></li>
                </ul>
            </div>
            <div class="wd-section">
                <table cellspacing="0" cellpadding="0" border="1" class="table-personal">
                    <tbody>
                        <tr class="titel">
                            <td style="width:5%">
                                <p><strong>Mã Sự Kiện</strong></p>
                            </td>
                            <td style="width:35%">
                                <p><strong>Sự kiện</strong></p>
                            </td>
                            <?php if ($owner){ ?>
                            <td style="width:15%">
                                <p><strong>Tình trạng</strong></p>
                            </td>
                            <?php } ?>
                            <td style="width:15%">
                                <p><strong>Đăng lúc</strong></p>
                            </td>
                            <?php if ($owner){ ?>
                            <td style="width:20%">
                                <p><strong>Đã tham gia</strong></p>
                            </td>
                            <td style="width:10%">
                                <p><strong>Tác vụ</strong></p>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php foreach($data as $event){ ?>
                        <tr class="content">
                            <td>
                                <p><?php echo $event['code']; ?></p>
                            </td>
                            <td>
                                <a href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'detail', 'slug' => Link::seoTitle($event['title']), 'id' => $event['id'])); ?>" target="_blank" ><p><span><?php echo $event['title'] ?></span></p></a>
                            </td>
                            <?php if ($owner){ ?>
                             <td>
                                <p><span class="<?php echo $event['approved']; ?>"></span></p>
                            </td>
                            <?php } ?>
                            <td>
                                <p><span class="date"><?php echo $event['created']; ?></span></p>
                            </td>
                            <?php if ($owner){ ?>
                             <td>
                                <a href="<?php echo $this->Html->url(array('controller' => 'Participations', 'action' => 'participationsOfEvent', $event['id'])) ?>"><p><span class="text-align"><?php echo $event['members']; ?> Thành viên  </span></p></a>
                            </td>
                            <td>
                                <p>
                                    <a href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'edit', $event['id'])); ?>" class="notes"></a>
                                    <a onclick="return confirm('Bạn có chắc là muốn xóa sự kiện này?');"  href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'delete', $event['id'])); ?>" class="deleted"></a>
                                </p>
                            </td>
                            <?php } ?>
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
            </div>
        </div>
    </div>
    
</div>


