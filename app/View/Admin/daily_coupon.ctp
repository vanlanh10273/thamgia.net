<h4 class="alert_info">Daily Coupon</h4>
<article class="module width_full">
    
    <header>
        <h3 class="tabs_involved">Daily Coupon</h3>
    </header>

    <div class="tab_container">
        <div id="tab1" class="tab_content">
            <table class="tablesorter" cellspacing="0"> 
            <thead> 
                <tr> 
                    <th>Sự Kiện</th> 
                    <th>Thành Phố</th> 
                    <th>Đăng Lúc</th> 
                    <th style="width: 100px;">Tương Tác</th>
                </tr> 
            </thead> 
            <tbody> 
                <?php foreach($data as $dailyCoupon){ ?>
                <tr>
                    <td><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'detail',
                                                                    'slug' => Link::seoTitle($dailyCoupon['title']),
                                                                    'id' => $dailyCoupon['id']));  ?>" target="_blank"><?php echo $dailyCoupon['title']; ?></a></td> 
                    <td><?php echo $dailyCoupon['city']; ?></td>
                    <td><?php echo $dailyCoupon['created']; ?></td>
                    <td>
                        <a class="installed" target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'editDailyCoupon', $dailyCoupon['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_edit.png" title="Chỉnh Sửa"></a> 
                        <a onclick="return confirm('Bạn có chắc là muốn xóa daily coupon này?');" href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'deleteDailyCoupon', $dailyCoupon['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_trash.png" title="Xóa"></a>
                    </td>
                </tr>    
                <?php } ?> 
            </tbody> 
            </table>
        </div><!-- end of #tab1 -->

    </div><!-- end of .tab_container -->

    <footer>
        <div class="submit_link">
            <input type="submit" class="alt_btn" onclick="window.location='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addDailyCoupon')); ?>'" value="Add">
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

