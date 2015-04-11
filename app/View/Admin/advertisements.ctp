<h4 class="alert_info">Quảng Cáo</h4>

<article class="module width_full">
    <header>
        <h3 class="tabs_involved">Quảng Cáo</h3>
    </header>

    <div class="tab_container">
        <div id="tab1" class="tab_content">
        <table class="tablesorter" cellspacing="0"> 
        <thead> 
            <tr> 
                <th>Thông tin</th>
                <th>Thành phố</th>
                <th>Bắt đầu</th> 
                <th>Kết thúc</th> 
                <th>Vị trí</th>
                <th>Kích thước</th>
                <th>Tương tác</th>
            </tr> 
        </thead> 
        <tbody> 
            <?php foreach($data as $advertisement){
             ?>
            <tr>
                <td><a target="_blank" href="<?php echo $advertisement['Advertisement']['url']; ?>"><?php echo $advertisement['Advertisement']['information']; ?></a></td>
                <td><?php echo $advertisement['Advertisement']['city']; ?></td>
                <td><?php echo $advertisement['Advertisement']['start']; ?></td>
                <td><?php echo $advertisement['Advertisement']['end']; ?></td>
                <td><?php echo $advertisement['Advertisement']['side']; ?></td>
                <td><?php echo $advertisement['Advertisement']['size']; ?></td>
                <td>
                    <a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'editAdvertisement', $advertisement['Advertisement']['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_edit.png" title="Duyệt"></a>
                    <a onclick="return confirm('Bạn có chắc là muốn xóa sự kiện này?');" href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'deleteAdvertisement', $advertisement['Advertisement']['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_trash.png" title="Xóa"></a>
                </td>
            </tr>
            <?php  } ?>
        </tbody> 
        </table>
        </div><!-- end of #tab1 -->

    </div><!-- end of .tab_container -->

    <footer>
        <div class="submit_link">
            <input type="submit" class="alt_btn" onclick="window.location='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addAdvertisement')); ?>'" value="Add">
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
