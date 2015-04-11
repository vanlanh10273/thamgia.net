<div class="content-event">
    <div class="content-titel">
        <ul class="menu-content">
            <li class="last">
                <a class="active" href="#">thông báo</a>
            </li>
        </ul>
    </div>
    <div class="management-message">
        <table cellspacing="0" cellpadding="0" border="1" width="100%">
            <tbody>
                <tr class="titel">
                    <td class="logged-involved">
                        <p>Nội Dung</p>
                    </td>
                    <td class="interactive">
                        <p>Xóa</p>
                    </td>
                </tr>
                <?php foreach($data as $item){ ?>
                <tr class="content">
                    <td class="update">
                        <a onclick="return viewNotification(this, <?php echo $item['Notification']['id']; ?>);" class="message-box <?php echo $item['Notification']['viewed'] ? 'viewed' : ''; ?>" href="<?php echo $item['Notification']['link']; ?>"><?php echo $item['Notification']['notification']; ?></span></a>
                    </td>
                    <td class="interactive">
                        <a class="delete"  onclick="return confirm('Bạn có muốn xóa thông báo này không?');"  href="<?php echo $this->Html->url(array('controller' => 'Notifications', 'action' => 'delete', $users_userid, $item['Notification']['id'])); ?>">delete</a> 
                    </td>
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