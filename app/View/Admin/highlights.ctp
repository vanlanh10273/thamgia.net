<h4 class="alert_info">Sự kiện nổi bật</h4>

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
                <th>Bắt đầu sự kiện</th> 
                <th>Bắt đầu</th> 
                <th>Kết thúc</th> 
                <th>Miễn phí</th>
                <th>Tương tác</th>
            </tr> 
        </thead> 
        <tbody> 
            <?php foreach($data as $highlight){
                $start_event = new DateTime($highlight['Highlight']['start_event']);
                $start = new DateTime($highlight['Highlight']['start']);
                $end = new DateTime($highlight['Highlight']['end']);
             ?>
            <tr>
                <td><a target="_blank" href="<?php echo $highlight['Highlight']['event_url']; ?>"><?php echo $highlight['Highlight']['title']; ?></a></td>
                <td><?php echo $start_event->format(TIME_FORMAT_CLIENT); ?></td>
                <td><?php echo $start->format(TIME_FORMAT_CLIENT); ?></td>
                <td><?php echo $end->format(TIME_FORMAT_CLIENT); ?></td>
                <td><?php echo $highlight['Highlight']['free'] ? 'x' : ''; ?></td>
                <td>
                    <a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'editHighlight', $highlight['Highlight']['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_edit.png" title="Duyệt"></a>
                    <a onclick="return confirm('Bạn có chắc là muốn xóa sự kiện này?');" href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'deleteHighlight', $highlight['Highlight']['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_trash.png" title="Xóa"></a>
                </td>
            </tr>
            <?php  } ?>
        </tbody> 
        </table>
        </div><!-- end of #tab1 -->

    </div><!-- end of .tab_container -->

    <footer>
        <div class="submit_link">
            <input type="submit" class="alt_btn" onclick="window.location='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addHighlight')); ?>'" value="Add">
        </div>
    </footer>
            
</article><!-- end of content manager article -->

<div class="paging">
<?php
  echo $this->Paginator->prev(__(''), array('tag' => 'span'));
  echo $this->Paginator->numbers(array('separator'=>'<span>-</span>'));      
  echo $this->Paginator->next(__(''), array('tag' => 'span'));
?>
</div>
