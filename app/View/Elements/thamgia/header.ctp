<?php 
    $default_search = "SEARCH..." ;
?>

<?php  
    if (!isset($city_id))
        $city_id = DEFAULT_CITY_ID;
        
    // get notifications
    $notifications = null;
    $countNotification = 0;
    if ($logged_in){
        $notifications = $this->requestAction(array('controller' => 'Notifications', 'action' => 'getNotifications', $users_userid));;
        $countNotification = $this->requestAction(array('controller' => 'Notifications', 'action' => 'countNewNotifications', $users_userid));
    }
    
    // get daily_coupon
    $countDailyCoupon = $this->requestAction(array('controller' => 'DailyCoupons', 'action' => 'countDailyCoupon', $city_id)); 
?>

<div id="header">
    <div class="center">
        <!-- Logo -->
        <a href="<?php echo $this->Html->url('/'); ?>" class="logo">logo</a>
        <!-- End Logo -->
        
        <!-- provinces -->
        <div class="provinces">
            <a href="#" class="titel-provinces"><?php echo $city_name; ?></a>
            <ul class="content-provinces">
                <?php foreach($cities as $city){ ?>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'Home', 
                                                                    'action' => 'city',
                                                                    'slug' => Link::seoTitle($city['City']['name']),
                                                                    'city_id' => $city['City']['id']))?>"  class="ico"><?php echo $city['City']['name']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <!-- End provinces -->
        
        <!-- Search -->
        <div class="search-input">
            <button class="button" title="Search" onclick="search()" type="submit"><span><span>Tìm</span></span></button>
            <input class="input-text"  id="search" type="text" onblur="if(this.value=='') this.value='<?php echo $default_search; ?>'" onfocus="if(this.value=='<?php echo $default_search; ?>') this.value='';" value="<?php echo $default_search; ?>"/>
            <a href="javascript:search();" class="main-search-arrow"></a>
            <div class="main-search">
                <div class="select-1">
                    <select id="type_id">
                        <option value="0"> Tất cả thể loại </option>
                        <?php foreach($types as $type){ ?>
                            <option value="<?php echo $type['Type']['id'] ?>"> <?php echo $type['Type']['name']; ?> </option>
                        <?php } ?>
                    </select>
                </div>
                <!--<p>
                    <input type="checkbox"  name="is_subscribed" title="" value="1" checked="checked" id="free" class="checkbox"/>
                    <label for="is_subscribed">Miễn phí</label>
                </p>-->
                <div id="wd-reservation">
                    <form id="wd-form-reservation" method="post" action="">
                        <fieldset>
                            <div class="wd-calendar">
                                <input type="text" id="fromdate" name="fromdate" class="validate[required]" onblur="if(this.value=='') this.value=''" onfocus="if(this.value=='') this.value='';" value=""/>
                            </div>
                            <span>Đến</span>
                            <div class="wd-calendar last">
                                <input type="text" id="todate" name="todate" class="validate[required]" onblur="if(this.value=='') this.value=''" onfocus="if(this.value=='') this.value='';" value=""/>
                            </div>
                        </fieldset>    
                    </form>
                </div>
            </div>
        </div>
        <!-- End search -->
        
        <?php if (!$logged_in){?>
        <ul class="login">
            
            <li class="login">
                <p><a class="login" href="#">Đăng nhập</a></p>
                <div class="login-checkout">
                    <form id="frm_contact2" name="frm_contact2" method="post" action="<?php echo $this->Html->url('/') ?>users/login">
                        <fieldset>
                            <ul>
                                <li>
                                    <input type="text" name="data[User][email]" id="name2" title="Sign up for our newsletter" class="input-text required-entry validate-email" value="Email" onfocus="javascript:if(this.value=='Email') this.value=''" onblur="if(this.value=='') this.value='Email'"/>
                                </li>
                                <li>
                                    <input type="text" name="data[User][password]" id="password-login" title="Sign up for our newsletter" class="input-text required-entry validate-email password" value="Password" />
                                </li>
                                <li class="control">
                                    <input type="checkbox" name="is_subscribed" title="" value="1" id="is_subscribed2" class="checkbox"/>
                                    <label for="is_subscribed">Tự động đăng nhập lần sau </label>
                                </li>
                                <li>
                                    <button type="submit" title="" class="button"><span>button</span></button>
                                    <a class="password" href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'forgotPassword')); ?>">Quên mật khẩu?</a>
                                </li>
                                <li class="last">
                                    <a href="javascript:" onclick="loginFacebook()" class="facebook">facebook</a>
                                </li>
                            </ul>
                        </fieldset>
                    </form>
                </div>
            </li>
            <li class="register"><a href="<?php echo $this->Html->url(array("controller" => "Signup", "action"=>"account")) ?>">Đăng ký</a></li>
        </ul>
        <?php } else{ ?>
        <div class="communication">
            <div class="notification">
                <a href="#" class="notification-infox"></a>
                
                <span id="infox" <?php if ($countNotification > 0) echo 'class="infox"'; ?> ><?php echo ($countNotification > 0 ? $countNotification : ''); ?></span>
                <div class="notifications">
                    <div id="scrollbar1">
                            <div class="scrollbar" style="height: 255px;">
                                <div class="track" style="height: 255px;">
                                    <div class="thumb" style="top: 0px; height: 122.22744360902256px;">
                                        <div class="end">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="viewport">
                                 <div class="overview" style="top: 0px;">
                                      <ul>
                                        <?php if (count($notifications)==0){ ?>
                                        <li>
                                            <span>Chưa có cập nhật nào gần đây</span>
                                        </li>
                                        <?php } ?>
                                        <?php foreach($notifications as $item){ ?>
                                        <li>
                                            <a <?php echo $item['Notification']['viewed'] ? '' : 'class="not-view"'; ?> onclick="return viewNotification(this, <?php echo $item['Notification']['id']; ?>);" target="_blank" href="<?php echo  $item['Notification']['link']; ?>">
                                                <img src="<?php echo $this->Html->url('/'). ( $item['Notification']['image_url'] != '' ? $item['Notification']['image_url'] : NO_IMG_URL );  ?>" alt="" />
                                                <span class="users01"><?php echo $item['Notification']['notification']; ?></span>
                                                <span class="time"><?php echo General::getTimeElapse($item['Notification']['created']); ?></span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                      </ul>
                                 </div>
                            </div>
                    </div>
                    <a class="view-all" href="<?php echo $this->Html->url(array("controller" => "Notifications", "action" => "index")); ?>">Xem tất cả</a>
                </div>    
            </div>
            <div class="users">
                <img width="29" height="29" alt="Image" src="<?php echo General::getUrlImage($users_avatar); ?>">
                <p class="names"><?php echo $users_name; ?></p>
            </div>
            <div class="login">
                <a href="#" class="login-block"></a>
                <ul class="new-control">
                    <li>
                        <a class="events-01" href="<?php echo $this->Html->url(array("controller" => "Events", "action" => "addedEvents", $users_userid)); ?>">Sự kiện đã đăng</a><p><?php echo $count_added_events; ?></p>
                    </li>
                    <li>
                        <a class="participated-in-events" href="<?php echo $this->Html->url(array("controller" => "Participations", "action" => "participatedEvents", $users_userid)); ?>">Sự kiện đã tham gia</a><p><?php echo $participated_events ?></p>
                    </li>
                    <li>
                        <a class="messages-01" href="<?php echo $this->Html->url(array("controller" => "Messages", "action" => "inbox")); ?>">Tin nhắn</a><p><?php echo $inbox_items; ?></p>
                    </li>
                    <li>
                        <a class="personal-information" href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "profile", $users_userid)); ?>">Thông tin cá nhân</a>
                    </li>
                    <li>
                        <a class="out" href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "logout")); ?>">Thoát</a>
                    </li>
                </ul>
            </div>
        </div>
        <?php }?>
        
     </div>
</div>
<div id="communication">
    <div class="center" id="center">
        <div class="pt-events">
            <ul>
                <li id="home">
                    <a href="<?php echo $this->Html->url('/'); ?>" class="pt-home"></a>
                </li>
                <li id="type_<?php echo TYPE_WORKSHOP; ?>"><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('HỘI THẢO'),
                                                                    'type_id' => TYPE_WORKSHOP )); ?>">HỘI THẢO</a>
                                                                    
                     <?php if ($total_workshop > 0){ ?> <span class=""><?php echo $total_workshop; ?></span> <?php } ?>
                </li>
                                                                    
                <li id="type_<?php echo TYPE_PROMOTION; ?>"><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('KHUYẾN MÃI'),
                                                                    'type_id' => TYPE_PROMOTION )); ?>">KHUYẾN MÃI</a>
                                                                    
                      <?php if ($total_promotion > 0){ ?> <span class="pt-01"><?php echo $total_promotion; ?></span> <?php } ?>                                                                                                                  
                </li>
                                                                    
                <li id="type_<?php echo TYPE_TRAINING; ?>"><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('ĐÀO TẠO'),
                                                                    'type_id' => TYPE_TRAINING )); ?>">ĐÀO TẠO</a>
                     
                     <?php if ($total_training > 0){ ?> <span class="pt-02"><?php echo $total_training; ?></span> <?php } ?>                                               
                </li>
                                                                    
                <li id="type_<?php echo TYPE_ENTERTAINMENT; ?>"><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('GIẢI TRÍ'),
                                                                    'type_id' => TYPE_ENTERTAINMENT )); ?>">GIẢI TRÍ</a>
                                                                    
                      <?php if ($total_entertainment > 0){ ?> <span class="pt-03"><?php echo $total_entertainment; ?></span> <?php } ?>
                </li>
                
                <li class="pt-button-nav">
                        <a href="#" ></a>
                        <ul class="content-events content-event-how pt-nav-sub-vertical">
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Khai Trương'),
                                                                    'type_id' => TYPE_INAUGURATED )); ?>" class="inaugurated">Khai Trương</a></li>
                                                                    
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Xã hội'),
                                                                    'type_id' => TYPE_SOCIAL )); ?>" class="social">Xã hội</a></li>
                                                                    
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Du lịch'),
                                                                    'type_id' => TYPE_TOURISM )); ?>" class="tourism">Du lịch</a></li>
                            
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Triển lãm'),
                                                                    'type_id' => TYPE_EXHIBITION )); ?>" class="exhibition">Triển lãm</a></li>
                            
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'Events', 
                                                                    'action' => 'getByType', 
                                                                    'slug_city' => Link::seoTitle($city_name), 
                                                                    'city_id' => $city_id,
                                                                    'slug_type' => Link::seoTitle('Văn hóa'),
                                                                    'type_id' => TYPE_CULTURE )); ?>" class="culture">Văn hóa</a></li>
                        </ul>
                    </li>
            </ul>
        </div>
        <?php if ($countDailyCoupon > 0){ ?>
        <div class="daily-coupon">
            <p><a href="<?php echo $this->Html->url(array('controller' => 'DailyCoupons', 'action' => 'index', 'slug' => LINK::seoTitle($city_name), 'city_id' => $city_id)); ?>">DAILY COUPON</a></p>
            <span><?php echo $countDailyCoupon; ?></span>
        </div>
        <?php } ?>
        <div class="post-events">
            <a onmouseover="bubbleAddEvent(this)" onmouseout="removeBubbleAddEvent(this)" href="#" <?php   echo $logged_in ? 'onclick="addEvent()"' : 'onclick="login()"' ?> >đăng sự kiện mới</a>
        </div>
    </div>
</div>            


<script type="text/javascript">
        $(document).ready(function() {
            $('#scrollbar1').tinyscrollbar();   
            $(".notification-infox").click(
                function(){
                    $(".notifications").toggleClass("notifications01");
                    if ($(".notifications").hasClass("notifications01")) {
                        $('#scrollbar1').tinyscrollbar();
                        $("#navigation .center").css("z-index", "100");
                    }else{
                        $("#navigation .center").css("z-index", "2000");
                    }
                }
            );  
                      
            $('#click-login').bind('click', function() {
                $('#fancybox-close').css('display','block');
            });
            
            $('p a.login').click(function(){
                if($(this).parent().hasClass('active')==false){
                   $(this).parent().addClass('active').siblings('p a.login').removeClass('active');
                    $(this).parent().next('.login-checkout').slideDown();
                     $(this).parent().next().siblings('.login-checkout').slideUp();
                     $("#navigation .center").css("z-index", "100");
                     
                }
                else{
                   if($(this).parent().next('.login-checkout').is(':hidden')==false){
                       $(this).parent().next('.login-checkout').slideUp();$(this).parent().removeClass('active');}
                else{ 
                    $(this).parent().next('.login-checkout').slideDown();}
                      $(this).parent().next().siblings('.login-checkout').slideUp();
                    $("#navigation .center").css("z-index", "2000");
                }
                    return false;
            });
            
            <?php if (!isset($city_id)){ ?>
                $("#click-city").fancybox({modal:true}).trigger('click');    
            <?php }?>
            
            $('.password').live('focus', function(){
                if($(this).val()=='Password'){                    
                    var id = $(this).attr('id');
                    $(this).replaceWith('<input id="'+ id +'"  class="input-text required-entry validate-email password" type="password"  name="data[User][password]" value="" />');
                    $('#' + id).focus();
                }
            }); 
            $('.password').live('blur', function(){
                if($(this).val()=='') {
                    var id = $(this).attr('id');
                    $(this).replaceWith('<input id="'+ id +'" class="input-text required-entry validate-email password"  type="text" name="data[User][password]" value="Password" /> ');
                }
            });
            
            $("#search").keyup(function(event){
                if(event.keyCode == 13){
                    search();
                }
            });
        });
        
        function login(){
            $("#click-login").fancybox({modal:true}).trigger('click');
            //return false;    
        }
        
        function changeCity(e){
            window.location = '<?php echo $this->Html->url(array('controller' => 'Home', 'action' => 'selectCity')) ?>/' + $(e).val();
        }
        
        function search(){
            var searchUrl = '<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'search')); ?>' + '/' + '<?php echo isset($city_id) ? $city_id : DEFAULT_CITY_ID;?>' + '/' + $('#type_id').val();
            //var free = $('#free').attr('checked') ? 1 : 0;
            var fromDate = $('#fromdate').val() != '' ? $('#fromdate').val() : '0';
            fromDate = fromDate.replace('/', '-');
            fromDate = fromDate.replace('/', '-');
            var toDate = $('#todate').val() != '' ? $('#todate').val() : '0';
            toDate = toDate.replace('/', '-');
            toDate = toDate.replace('/', '-');
            var title = $('#search').val() != '<?php echo $default_search; ?>' ? $('#search').val() : '0';
            searchUrl += '/' + fromDate + '/' + toDate + '/' + title;
            window.location = searchUrl;
        }   
        function viewNotification(e, notification_id){
            $(e).removeClass('not-view');
            
            // change notification infor
            var countNotification = $('#notification-infor').text();
            if (countNotification == '1'){
                $('#notification-infor').css('display', 'none');
            }else{
                $('#notification-infor').text(countNotification - 1);
            }
            
            // mark viewed for notification
            $.ajax({
               type:"GET",
               async:false,
               url:'<?php echo $this->Html->url(array('controller' => 'Notifications','action' => 'markViewedNotification')) ?>' + '/' + notification_id,
               success : function(data) {
                    return true;      
               },
               error : function() {
                   return false;
               },
           });
           
           return true;
        }
        function addEvent(){
            window.location = '<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'add')); ?>';
        }
        function bubbleAddEvent(e){
            $(e).CreateBubblePopup({

                position : 'top',
                align     : 'center',
                
                innerHtml: 'Đăng sự kiện hoàn toàn miễn phí',            

                innerHtmlStyle: {
                                    color:'#ffffff', 
                                    'text-align':'center'
                                },

                themeName: 'all-azure',
                themePath: '<?php echo $this->Html->url('/'); ?>' + 'img/jquerybubblepopup-themes'
             
            });
        }
        
        function removeBubbleAddEvent(e){
            $(e).RemoveBubblePopup();
        }
        
        function loginFacebook(){
            $.oauthpopup({
                path: '<?php echo BASE_URL; ?>FacebookCps/login',
                width:600,
                height:300,
                callback: function(){
                    window.location.reload();
                }
            });
        }
</script>