<h4 class="alert_info">Sự kiện mới đăng</h4>
<article class="module width_full">
    <header>
        <h3 class="tabs_involved">SỰ KIỆN CHỜ DUYỆT</h3>
    </header>

    <div class="tab_container">
        <div id="tab1" class="tab_content">
        <table class="tablesorter" cellspacing="0"> 
        <thead> 
            <tr> 
                <th>Sự Kiện</th> 
                <th>Thành Phố</th> 
                <th>Thể Loại</th> 
                <th>Đăng Lúc</th> 
                <th>Cập Nhật</th>
                <th>Người Đăng</th>
                <th>Tương Tác</th>
            </tr> 
        </thead> 
        <tbody> 
            <?php foreach($data as $event){ ?>
            <tr>
                <td><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                'action' => 'detail',
                                                                'slug' => Link::seoTitle($event['title']),
                                                                'id' => $event['id']));  ?>" target="_blank"><?php echo $event['title']; ?></a></td> 
                <td><?php echo $event['city']; ?></td>
                <td><?php echo $event['type']; ?></td>
                <td><?php echo $event['created']; ?></td>
                <td><?php echo $event['updated']; ?></td>
                <td><?php echo $event['user']; ?></td>
                <td>
                    <a href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'approve', $event['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_alert_success.png" title="Duyệt"></a>
                    <a class="installed" target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'edit', $event['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_edit.png" title="Chỉnh Sửa"></a> 
                    <a onclick="return confirm('Bạn có chắc là muốn xóa sự kiện này?');" href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'delete', $event['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_trash.png" title="Xóa"></a>
                </td>
            </tr>    
            <?php } ?> 
        </tbody> 
        </table>
        </div><!-- end of #tab1 -->

    </div><!-- end of .tab_container -->

            
</article><!-- end of content manager article -->

<div class="paging">
<?php
  echo $this->Paginator->prev(__(''), array('tag' => 'span'));
  echo $this->Paginator->numbers(array('separator'=>'<span>-</span>'));      
  echo $this->Paginator->next(__(''), array('tag' => 'span'));
?>
</div>

