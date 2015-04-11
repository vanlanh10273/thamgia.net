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
                    <li ><a href="<?php echo $this->Html->url(array("controller" => "Events", "action" => "addedEvents", $user_id)); ?>"><span><?php echo $userInformation['count_added_events']; ?></span>Sự kiện đã đăng</a><span class="border"></span></li>
                    <li class="active"><a href="<?php echo $this->Html->url(array("controller" => "Participations", "action" => "participatedEvents", $user_id)); ?>"><span><?php echo $userInformation['count_participated_events']; ?></span>Sự kiện đã tham gia</a><span class="border"></span></li>
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
                                <p><strong>Mã sự kiện</strong></p>
                            </td>
                            <td style="width:35%">
                                <p><strong>Sự kiện</strong></p>
                            </td>
                            <td style="width:15%">
                                <p><strong>Bắt đầu</strong></p>
                            </td>
                            <td style="width:15%">
                                <p><strong>Kết thúc</strong></p>
                            </td>
                            <td style="width:15%">
                                <p><strong>Tham gia lúc</strong>
                            </td>
                            <?php if ($owner){ ?>
                            <td style="width:15%">
                                <p><strong>Tương tác</strong></p>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php foreach($data as $participation){ ?>
                        <tr class="content">
                           <td>
                                <p><span><?php echo $participation['code']; ?></span></p>
                            </td>
                            <td>
                                 <?php if ($owner){ ?>
                                <a href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'detail', 'slug' => Link::seoTitle($participation['title']) , "id" => $participation['event_id'])); ?>" target="_blank" ><span><?php echo $participation['title'] ?></span></a>
                                <?php }else{ ?>
                                <a href="#" ><span><?php echo $participation['title'] ?></span></a>
                                <?php } ?>
                            </td>
                            <td>
                                <p><span class="date"> <?php echo $participation['start']; ?></span></p>
                            </td>
                            <td>
                                <p><span class="date"> <?php echo $participation['end']; ?></span></p>
                            </td>
                             <td>
                                <p><span class="date"> <?php echo $participation['created']; ?></span></p>
                            </td>
                             <?php if ($owner){ ?>
                            <td>
                                <a class="delete" onclick="return confirm('Bạn có chắc là muốn xóa đăng ký sự kiện này?');"  href="<?php echo $this->Html->url(array('controller' => 'Participations', 'action' => 'delete',$users_userid ,$participation['event_id'])); ?>"><img src="<?php echo $this->Html->url('/')?>images/icn_trash.png" /></a>
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
