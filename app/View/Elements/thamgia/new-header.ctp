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

<div id="pt-head-container">
	<div class="container-fluid">
		<div class="row">
			<div class="block">
				<div class="pt-address pull-left">
					<a href="#" class="pt-address-info"><i class="fa fa-map-marker"></i><span><?php echo $city_name; ?></span><i class="fa fa-chevron-down"></i></a>
					<ul class="pt-list-address">
                        <?php foreach($cities as $city){ ?>
                            <li>
                                <a href="<?php echo $this->Html->url(array('controller' => 'Home', 
                                                                    'action' => 'city',
                                                                    'slug' => Link::seoTitle($city['City']['name']),
                                                                    'city_id' => $city['City']['id']))?>">
                                    <i class="fa fa-map-marker"></i> <?php echo $city['City']['name']; ?>
                                </a>
                            </li>
                        <?php } ?>
					</ul>
				</div>
				<div class="pt-search pull-left">
					<button class="button-search" title="Search" type="submit"><i class="fa fa-search"></i></button>
					<input class="input-text" type="text" onblur="if(this.value=='') this.value='Tìm kiếm...'" onfocus="if(this.value=='Tìm kiếm...') this.value='';" value="Tìm kiếm...">
					<div class="pt-block-content-search">
						<div class="pt-select">
							<i class="fa fa-angle-down"></i>
							<select>
								<option value="0"> Tất cả thể loại</option>
                                <?php foreach($types as $type){ ?>
                                    <option value="<?php echo $type['Type']['id'] ?>"> <?php echo $type['Type']['name']; ?> </option>
                                <?php } ?>
							</select>
						</div>
						<div class="pt-datepicker">
							<input name="data[Booking][arrivaldate]" id="dpd1" class="span2 icon-input" data-date-format="dd-mm-yyyy" placeholder="" type="text">
						</div>
						<div class="pt-den">
							Đến
						</div>
						<div class="pt-datepicker">
							<input name="data[Booking][departuredate]" id="dpd2" class="span2 icon-input" data-date-format="dd-mm-yyyy" placeholder="" type="text">
						</div>
					</div>
				</div>
				<div class="pt-login pull-right">
                    <?php if (!$logged_in){?>
					   <a href="#login" class="link-login">Đăng nhập</a>
                    <?php } else{ ?>
                        <div class="">
							<div class="pt-setting-user">
								<div class="dropdown">
							        <button class="btn dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
							        <ul class="dropdown-menu dropdown-menu-right dropdown-row" role="menu" aria-labelledby="menu1">
								        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" href="<?php echo $this->Html->url(array("controller" => "Events", "action" => "addedEvents", $users_userid)); ?>">
                                                <i class="fa fa-flag"></i> Sự kiện đã đăng<span><?php echo $count_added_events; ?></span>
                                            </a>
                                        </li>
								        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" href="<?php echo $this->Html->url(array("controller" => "Participations", "action" => "participatedEvents", $users_userid)); ?>">
                                                <i class="fa fa-heart"></i> Sự kiện đã tham gia<span><?php echo $participated_events ?></span>
                                            </a>
                                        </li>
								        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" href="<?php echo $this->Html->url(array("controller" => "Messages", "action" => "inbox")); ?>">
                                                <i class="fa fa-envelope-o"></i> Tin nhắn <span><?php echo $inbox_items; ?></span>
                                            </a>
                                        </li>
								        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "profile", $users_userid)); ?>">
                                                <i class="fa fa-user"></i> Thông tin cá nhân
                                            </a>
                                        </li>
								        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" href="<?php echo $this->Html->url(array("controller" => "Users", "action" => "logout")); ?>">
                                                <i class="fa fa-sign-out"></i> Thoát
                                            </a>
                                        </li>
							        </ul>
							    </div>
							</div>

							<div class="btn-group pt-notify">
								<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								    <span class="number"><?php echo $countNotification; ?></span>
								    <i class="fa fa-bell"></i>
								</button>
								<div class="dropdown-menu dropdown-menu-right dropdown-row pt-block-stt" role="menu">
									<div class="pt-info-content-stt">
										<div class="pt-niceScroll">
											<ul class="pt-list-comment">
                                                <?php if (count($notifications)==0){ ?>
                                                <li>
                                                    <span>Chưa có cập nhật nào gần đây</span>
                                                </li>
                                                <?php } ?>
                                                <?php foreach($notifications as $item){ ?>
												<li>
													<div class="block-user">
														<a href="#">
															<img src="<?php echo $this->Html->url('/'). ( $item['Notification']['image_url'] != '' ? $item['Notification']['image_url'] : NO_IMG_URL );  ?>" alt="">
															<div class="block-content-text">
																<h3 class="title"><span class="user-name"><?php echo $item['Notification']['notification']; ?></span>vừa đăng sự kiện <strong>Đà tạo chứng chỉ văn thư lưu trữ trên toàn quốc</strong></h3>
																<p><?php echo General::getTimeElapse($item['Notification']['created']); ?></p>
															</div>
														</a>
													</div>
												</li>
                                                <?php } ?>
												<li>
													<div class="block-user">
														<a href="#">
															<img src="img/images/sk2.jpg" alt="">
															<div class="block-content-text">
																<h3 class="title"><span class="user-name">Hồng Nhung</span>vừa đăng sự kiện <strong>Đà tạo chứng chỉ văn thư lưu trữ trên toàn quốc</strong></h3>
																<p>1 giờ trước</p>
															</div>
														</a>
													</div>
												</li>
												<li>
													<div class="block-user">
														<a href="#">
															<img src="img/images/sk2.jpg" alt="">
															<div class="block-content-text">
																<h3 class="title"><span class="user-name">Hồng Nhung</span>vừa đăng sự kiện <strong>Đà tạo chứng chỉ văn thư lưu trữ trên toàn quốc</strong></h3>
																<p>1 giờ trước</p>
															</div>
														</a>
													</div>
												</li>
												<li>
													<div class="block-user">
														<a href="#">
															<img src="img/images/sk2.jpg" alt="">
															<div class="block-content-text">
																<h3 class="title"><span class="user-name">Hồng Nhung</span>vừa đăng sự kiện <strong>Đà tạo chứng chỉ văn thư lưu trữ trên toàn quốc</strong></h3>
																<p>1 giờ trước</p>
															</div>
														</a>
													</div>
												</li>
												<li>
													<div class="block-user">
														<a href="#">
															<img src="img/images/sk2.jpg" alt="">
															<div class="block-content-text">
																<h3 class="title"><span class="user-name">Hồng Nhung</span>vừa đăng sự kiện <strong>Đà tạo chứng chỉ văn thư lưu trữ trên toàn quốc</strong></h3>
																<p>1 giờ trước</p>
															</div>
														</a>
													</div>
												</li>
												<li>
													<div class="block-user">
														<a href="#">
															<img src="img/images/sk2.jpg" alt="">
															<div class="block-content-text">
																<h3 class="title"><span class="user-name">Hồng Nhung</span>vừa đăng sự kiện <strong>Đà tạo chứng chỉ văn thư lưu trữ trên toàn quốc</strong></h3>
																<p>1 giờ trước</p>
															</div>
														</a>
													</div>
												</li>
												<li>
													<div class="block-user">
														<a href="#">
															<img src="img/images/sk2.jpg" alt="">
															<div class="block-content-text">
																<h3 class="title"><span class="user-name">Hồng Nhung</span>vừa đăng sự kiện <strong>Đà tạo chứng chỉ văn thư lưu trữ trên toàn quốc</strong></h3>
																<p>1 giờ trước</p>
															</div>
														</a>
													</div>
												</li>
											</ul>
										</div>
									</div>
								 </div>
							</div>
							

							<div class="pt-user-info">
								<a href="#">
									<h3><?php echo $users_name; ?></h3>
									<img src="<?php echo General::getUrlImage($users_avatar); ?>" alt=""/>
								</a>
							</div>

						</div>
                    <?php } ?>
				</div>
				
			</div>
		</div>
	</div>
</div>
<div class="popup-exchange">
	<div class="popup-login" id="login">
		<div class="login-checkout">
			<h3 class="title">Đăng nhập</h3>
        	<form id="frm_contact" name="frm_contact" method="post" accept-charset="utf-8" action="<?php echo $this->Html->url('/') ?>users/login">
            	<fieldset>
                	<ul>
                    	<li>
                    		<label>Email</label>
                        	<input type="text" name="data[User][email]" id="name"/>
                        </li>
                        <li>
                        	<label>Password</label>
                            <input type="password" name="data[User][password]" id="password" >
                        </li>
                        <li>
                        	<button type="submit" title="" class="button">Đăng nhập</button>
                        </li>
                        <li class="control">
                        	<input type="checkbox" name="is_subscribed" title="" value="1" id="is_subscribed" class="checkbox">
                            <label for="is_subscribed">Ghi nhớ đăng nhập</label>
                            <a href="#" class="password">Quên mật khẩu?</a>
                        </li>
                        <li class="last">
                        	<a href="#" class="facebook"><i class="fa fa-facebook"></i> Đăng nhập qua facebook</a>
                        </li>
                    </ul>
                     <div class="link-sigup-popup">
                        	Bạn chưa có tài khoản. <a href="#sigup-popup">Đăng ký ngay tại đây</a>
                      </div>
                </fieldset>
            </form>
        </div>
	</div>
</div>

<div class="popup-exchange">
	<div class="popup-login" id="sigup-popup">
		<div class="login-checkout" style="width:350px;">
			<h3 class="title">Đăng ký</h3>
        	<form id="frm_contact" name="frm_contact" method="post" accept-charset="utf-8" action="<?php echo $this->Html->url('/') ?>signup/account">
            	<fieldset>
                	<ul>
                    	<li>
                    		<label>Email</label>
                        	<input type="text" name="data[Account][email]" id="name"/>
                        </li>
                        <li>
                        	<label>Password</label>
                            <input type="password" name="data[Account][password]" id="password" >
                        </li>
                        <li>
                        	<label>Nhập lại Password </label>
                            <input type="password" name="password2" id="password" >
                        </li>
                        <li class="code">
                        	<label>Code</label>
                        	<img src="<?php echo $this->Html->url(array('controller' => 'Signup', 'action'=>'captcha_image')); ?>" alt="">
                            <input type="text" name="data[Account][captcha_code]" id="code" >
                        </li>
                        <li>
                        	<button type="submit" title="" class="button">Đăng ký</button>
                        </li>
                        <li class="control">
                        	<input type="checkbox" name="is_subscribed" title="" value="1" id="is_subscribed" class="checkbox">
                            <label for="is_subscribed">Tôi đã đọc và đồng ý với</label>
                            <a href="#" class="thamgia">Các điều khoản của Thamgia.net</a>
                        </li>
                    </ul>
                     <div class="link-sigup-popup">
                        	Bạn đã có tài khoản. <a href="#login">Đăng nhập</a>
                      </div>
                </fieldset>
            </form>
        </div>
	</div>
</div>
<script type="text/javascript">
        $(document).ready(function() {  
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
            $(".link-login").trigger('click');
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