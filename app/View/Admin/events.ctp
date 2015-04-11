<h4 class="alert_info">Sự kiện đã duyệt</h4>
<div class="search">
    <label>Sự Kiện</label> <input id="search_title" style="width: 40%; margin-right: 10px;" value="<?php echo $search_title; ?>" />
    <label>Thành Phố</label>
    <select id="search_city">
        <option value="0">Tất cả</option>
        <?php foreach($cities as $city){ ?>
        <option value="<?php echo $city['City']['id'];?>"><?php echo $city['City']['name']; ?></option>
        <?php } ?>
    </select>
    <input type="submit" onclick="search();" value="Search" class="alt_btn">
</div>
<article class="module width_full">
    <header>
        <h3 class="tabs_involved">SỰ KIỆN ĐÃ DUYỆT</h3>
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
                <th>Người Đăng</th>
                <th style="width: 80px;">Tương Tác</th>
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
                <td><?php echo $event['user']; ?></td>
                <td>
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

<script type="text/javascript">
    function search(){
        var cityId = $("#search_city").val();
        var title  = encodeURI($("#search_title").val());
        var searchUrl = '<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'events' )); ?>' + '?cityId=' + cityId + '&title=' + title; 
        window.location = searchUrl;
    }
</script>
