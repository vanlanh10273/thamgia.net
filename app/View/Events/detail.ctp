<script type="text/javascript">
     $(document).ready(function(){
        loadComment(<?php echo $data['id']; ?>);
     });
     
     
     function loadComment(event_id){
        $.ajax({
               type:"GET",
               async:false,
               url:'<?php echo $this->Html->url('/')?>' + 'comments/getComments/' + event_id,
               success : function(data) {
                    $('#list-comments').empty();
                    $('#list-comments').append(data); 
               },
               error : function() {
                   alert('Lỗi load sự kiện bình luận!');
               },
           });
           
           loadHeaderComment(<?php echo $data['id']; ?>);
           loadUsersLike(<?php echo $data['id']; ?>);    
     }
     
     function sendComment(id){
        var comment = $('#comment-content').val();
        if ($('#comment-content').val() != ''){
            showLoading();
            
            dataString = 'comment=' + comment;
            $.ajax({
                url: '<?php echo $this->Html->url('/')?>' + 'comments/sendComment/'+id,
                type: 'POST',
                data: dataString,
                async: false,
                cache: false,
                timeout: 30000,
                error: function(){
                    return true;
                },
                success: function(msg){ 
                    loadComment(id);
                    loadHeaderComment(id);
                    hideLoading();
                    $('#comment-content').val(''); 
                }
            });
            
            
            dataString = 'event_id=' + id + '&comment=' + comment + '&commenter_id=' + '<?php echo $users_userid; ?>' + '&commenter=' + '<?php echo $users_name; ?>';
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'Comments', 'action' => 'sendMailNotificationComment'))?>',
                type: 'POST',
                data: dataString,
                async: true,
                cache: false,
                timeout: 1000,
                global: false,
                error: function(){
                    // do nothing
                }
                /*success: function(msg){ 
                    // do nothing
                } */
            });
            
               
        }else{
            alert('Vui lòng nhập nội dung bình luận!');
        }
        
     }
     
     function loadHeaderComment(event_id){
        $.ajax({
               type:"GET",
               async: false,
               url:'<?php echo $this->Html->url('/')?>' + 'comments/getHeaderComments/' + event_id,
               success : function(data) {
                    $('#sumary-comments').empty();
                    $('#sumary-comments').append(data); 
               },
               error : function() {
                   alert('Lỗi load header comment!');
               },
           });
     }
     
     function loadUsersLike(event_id){
        $.ajax({
               type:"GET",
               async: false,
               url:'<?php echo $this->Html->url('/')?>' + 'likes/getUsersLike/' + event_id,
               success : function(data) {
                    $('#users-like').empty();
                    $('#users-like').append(data); 
               },
               error : function() {
                   alert('lỗi load số like!');
               },
           });   
     }
     
     function like(event_id){
        $.ajax({
               type:"GET",
               async: false,
               url:'<?php echo $this->Html->url('/')?>' + 'likes/addLike/' + event_id + '/1',
               success : function(data) {
                    $('#users-like').empty();
                    $('#users-like').append(data); 
               },
               error : function() {
                   alert('lỗi like!');
               },
        });    
     }
     
     function disLike(event_id){
        $.ajax({
               type:"GET",
               async: false,
               url:'<?php echo $this->Html->url('/')?>' + 'likes/addLike/' + event_id + '/0',
               success : function(data) {
                    $('#users-like').empty();
                    $('#users-like').append(data); 
               },
               error : function() {
                   alert('lỗi dislike!');
               },
        });    
     }
     
     function showLoading(){
        $('#loading').css('display','inline-block');
     }
     
     function hideLoading(){
        $('#loading').css('display','none');
     }
     
     
</script>

<div class="events-content">
    <h2 class="titel">GIỚI THIỆU SỰ KIỆN</h2>
    <a href="javascript: window.history.back();" class="prev">Quay lại</a>
    <h3 class="titel-event"><?php echo isset($data['title']) ? $data['title'] : '';?></h3>
    <ul class="communication-user">
        <li>
            <p><span>Đăng ngày: </span><?php echo isset($data['created']) ? $data['created'] : '';  ?></p>
        </li>
        <li class="last">
            <p><span>Người đăng: </span><a href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'profile', $data['owner_id'])); ?>"><?php echo isset($data['user_name']) ? $data['user_name'] : '';  ?></a></p>
        </li>
        <li>
            <iframe frameborder="0" width="120" height="30" style="margin-top: -10px;" scrolling="no" src="<?php echo $this->Html->url(array(
                                            "controller"=>"Events", 
                                            "action"=> "shareFacebook",
                                            $data['id'], Link::seoTitle($data['title']),
                                            )); ?>">
            </iframe>
            <!-- Facebook Share Button --> 
            <!--<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a>
            <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>-->
            <!-- / Facebook Share Button -->   
        </li>
    </ul>
    <p class="img-events">
        <img src="<?php echo $this->Html->url('/') . (isset($data['image']) ? $data['image'] : NO_IMG_URL);  ?>" alt="Image"  />
    </p>
    <?php if($data['is_daily_coupon'] && $logged_in && $participated) echo $this->element('thamgia/voucher',array('event_id' => $data['id'])); ?>
    
    <table class="wd-style">
        <tr class="wd-tr-01">
            <td class="wd-td-01">Thể loại</td>
            <td class="wd-td-02"><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($data['city_name']), 
                                                                    'city_id' => $data['city_id'],
                                                                    'slug_type' => Link::seoTitle($data['type_name']),
                                                                    'type_id' => $data['type_id'])); ?>" > <?php echo isset($data['type'])? $data['type'] : '' ?></a></td>
        </tr>
        <tr>
            <td class="wd-td-01">Địa điểm</td>
            <td class="wd-td-02"><?php echo isset($data['address']) ? $data['address'] : ''; ?></td>
        </tr>
        <tr class="wd-tr-01">
            <td class="wd-td-01">Thời gian</td>
            <td class="wd-td-02"><?php echo isset($data['start']) ? $data['start'] : '';?>   -   <?php echo isset($data['end']) ? $data['end'] : '';?></td>
        </tr>
        <tr>
            <td class="wd-td-01">Hotline</td>
            <td class="wd-td-02"><?php echo isset($data['hotline']) ? $data['hotline'] : '';?></td>
        </tr>
        <tr class="wd-tr-01">
            <td class="wd-td-01"><?php echo $data['is_daily_coupon'] ? 'Chiết khấu' : 'Phí tham dự' ?></td>
            <td class="wd-td-02"><?php echo isset($data['fee'])? $data['fee'] : ''; ?></td>
        </tr>
    </table>
    <div class="participation">
        <?php if (!$participated && (($eventStatus == STATUS_UP_COMING) || ($eventStatus == STATUS_ON_GOING))){ ?>  
            <?php if ($data['is_daily_coupon']){ ?>
                    <a class="button-voucher" <?php   echo $logged_in ? 'onclick="participate()"' : 'onclick="login()"' ?> href="#"><span>submit</span></a>
                <?php }else{ ?>
                    <a class="participation-link"  href="javascript:<?php echo $logged_in ? "participate()" : 'login()';  ?>"  <?php   echo $logged_in ? 'onclick="participate()"' : 'onclick="login()"' ?> title="" type="submit">
                    <span>submit</span></a>
                <?php } ?>
        <?php } ?> 
    </div>
    
</div>

<div class="events-content events-content-01">
    <h2 class="titel">NỘI DUNG SỰ KIỆN </h2>
    <?php 
            $description = isset($data['description']) ? $data['description'] : '';
            $summary = General::getSummary($description);
            if ($summary != ''){
        ?>
        <div id="summary" style="display:block;">
            <?php 
                /*preg_match('/^([^.!?\s]*[\.!?\s]+){0,30}/', strip_tags($description), $abstract);
                echo $abstract[0];*/
                echo $summary;
            ?>
        </div>
        <div id="full" style="display: none;">
            <?php echo $description; ?>
        </div>
        <div class="view-more">
            <span></span><a id="link" onclick="return show_hide()" href="#" class="open">Xem thêm</a>
        </div>       
        <?php } else { ?>
        <div id="full" style="display: block;">
            <?php echo $description; ?>
        </div>
        <?php } ?>
</div>

<div class="block-events-comment">
    <h2 class="titel" id="sumary-comments"> BÌNH LUẬN</h2>
    <ul>
        <li>
            <?php if ($logged_in){ ?>
                <div>
                    <img src="<?php echo General::getUrlImage($users_avatar); ?>" alt="Image">
                    <textarea id="comment-content" cols="50" rows="10"  ></textarea>
                </div>
                <div>
                    <button class="wd-send" id="btn-comment" onclick="sendComment(<?php echo $data['id'] ?>)">Gởi Bình Luận</button>
                </div>
            <?php } else { ?>
                <p><span>Bạn vui lòng <a class="message" href="#" onclick="login(); return false;" >đăng nhập</a> trước để có thể tham gia bình luận</span></p>
            <?php } ?>
            <div id="loading">
                <img src="<?php echo $this->Html->url('/') . IMG_LOADER;  ?>" alt="" />
            </div> 
        </li>
        
    </ul>
    <ul id="list-comments">
        
    </ul>
</div>
                    
<!--<ul class="users-like" id="users-like">

</ul>-->

<div class="events-poster">
    </div>
    <div id="comments" class="block-events-03">
        
        
               
        
    </div>
    <?php  echo $this->element('thamgia/events_other', array(
                                                            'type_id'=> $data['type_id'],
                                                            'type_name' => $data['type_name'],
                                                            'event_id'=> $data['id']
                                                            )); ?>
</div>


          




