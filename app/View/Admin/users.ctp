<h4 class="alert_info">Có <?php echo $countUser; ?> Users</h4>
<div class="search">
    <label>Email</label> <input id="search_email" style="width: 20%; margin-right: 10px;" value="<?php echo $search_email; ?>" />
    <label>Tên</label> <input id="search_name" style="width: 20%; margin-right: 10px;" value="<?php echo $search_name; ?>" />
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
        <h3 class="tabs_involved"> Users</h3>
    </header>

    <div class="tab_container">
        <div id="tab1" class="tab_content">
        <table class="tablesorter" cellspacing="0"> 
        <thead> 
            <tr> 
                <th>Email</th> 
                <th>Tên</th> 
                <th>Quyền</th>
                <th>Thành phố</th> 
                <th>Lĩnh vực</th> 
                <th>Hình đại diện</th>
                <th>Tương tác</th>
            </tr> 
        </thead> 
        <tbody> 
            <?php foreach($data as $user){?>
            <tr>
                <td><?php echo $user['User']['email']; ?></td>
                <td><?php echo $user['User']['fullname']; ?></td>
                <td><?php echo $user['User']['level']; ?></td>
                <td><?php echo $user['User']['city']; ?></td>
                <td><?php echo $user['User']['career']; ?></td>
                <td><img src="<?php  echo General::getUrlImage($user['User']['avatar_url']); ?>" alt="" /></td>
                <td>
                    <a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'editUser', $user['User']['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_edit.png" title="Duyệt"></a>
                    <a onclick="return confirm('Bạn có chắc là muốn xóa sự kiện này?');" href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'deleteUser', $user['User']['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_trash.png" title="Xóa"></a>
                </td>
            </tr>
            <?php  } ?>
        </tbody> 
        </table>
        </div><!-- end of #tab1 -->

    </div><!-- end of .tab_container -->

    <footer>
        <div class="submit_link">
            <input type="submit" class="alt_btn" onclick="window.location='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addUser')); ?>'" value="Add">
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

<script type="text/javascript">
    function search(){
        var cityId = $("#search_city").val();
        var name  = encodeURI($("#search_name").val());
        var email  = encodeURI($("#search_email").val());
        var searchUrl = '<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'users' )); ?>' + '?cityId=' + cityId + '&name=' + name + '&email=' + email; 
        window.location = searchUrl;
    }
</script>