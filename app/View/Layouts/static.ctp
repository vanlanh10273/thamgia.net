<?php
$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset(); ?>
    <title><?php echo $title; ?></title>
  
    <meta name="description" content="thamgia.net - Mạng chia sẻ sự kiện, hội thảo, khóa đào tạo, triển lãm, khai trương và sự kiện cộng đồng khác diễn ra tại các thành phố lớn. Hòa mạng tham gia, mở ra cơ hội." />
    <meta name="keywords" content="sự kiện, hội thảo, triển lãm, khai trương, khánh thành, sự kiện Đà Nẵng, su kien Da nang, hội thảo Đà Nẵng, hoi thao Da nang, su kien Ha noi, sự kiện Hà nội, hội thảo Hà nội, hội thảo tp HCM, chia se su kien, dien gia, diễn giả, nguoi khong lo, người khổng lồ, hội thảo du học, hoi thao du học, tham gia" />
    <!--<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.8.21/themes/ui-lightness/jquery-ui.css" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>-->
    <?php
        echo $this->Html->meta('icon');
   
        echo $this->Html->css('common');
        echo $this->Html->css('validationEngine.jquery');
        echo $this->Html->css('validationEngine-day');
        echo $this->Html->css('jquery-fancybox');
        echo $this->Html->css('messageBox');
        echo $this->Html->css('jquery-ui-timepicker-addon');
        //echo $this->Html->css('cake.generic');
        
        echo $this->Html->script('jquery-1.5.1.min');
        echo $this->Html->script('cufon-yui');
        echo $this->Html->script('UTM_Nokia_StandardB_700.font');
        echo $this->Html->script('jquery.ui.core');
        echo $this->Html->script('jquery.ui.min.js');
        echo $this->Html->script('ui.datepicker');
        echo $this->Html->script('validationEngine');
        echo $this->Html->script('validationEngine-vi');
        echo $this->Html->script('jquery.fancybox-1.3.4.pack');
        echo $this->Html->script('tipped');
        echo $this->Html->script('category.cycle');
        echo $this->Html->script('link');
        echo $this->Html->script('stickyscroll');
        echo $this->Html->script('common');        
        

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
    ?>
</head>
<body>
    <div id="header" class="header-static">
        <div class="center">
            <a href="#" class="logo">logo</a>
            <div class="provinces">
                <select class="select">
                    <option>Đà Nẵng</option>
                    <option>Hà Nội</option>
                    <option>Hồ Chí Minh</option>
                </select>
            </div>
            <div class="search-input">
                <input id="search" class="input-text" type="text" maxlength="128" value="" name="q" autocomplete="off"/>
                <button class="button" title="Search" type="submit"><span><span>Tìm</span></span></button>
                <p><a href="#" class="main-search-arrow"></a></p>
                <div class="main-search">
                    <div class="select-1">
                        <select>
                            <option>Đà Nẵng</option>
                            <option>Hà Nội</option>
                            <option>Hồ Chí Minh</option>
                        </select>
                    </div>
                    <p><input type="checkbox" name="is_subscribed" title="" value="1" id="is_subscribed" class="checkbox"/>
                                        <label for="is_subscribed">Miễn phí</label></p>
                    <div id="wd-reservation">
                        <form id="wd-form-reservation" method="post" action="">
                            <fieldset>
                                <div class="wd-calendar">
                                    <input type="text" id="fromdate" name="fromdate" class="validate[required]" />
                                </div>
                                <span>Đến</span>
                                <div class="wd-calendar last">
                                    <input type="text" id="todate" name="todate" class="validate[required]" />
                                </div>
                            </fieldset>    
                        </form>
                    </div>
                </div>
            </div>
            <ul class="login">
                <li class="login">
                    <p><a class="login" href="#">Đăng nhập</a></p>
                    <div class="login-checkout">
                        <form id="frm_contact" name="frm_contact" method="post" accept-charset="utf-8">
                            <fieldset>
                                <ul>
                                    <li>
                                        <input type="text" name="name" id="name" title="Sign up for our newsletter" class="input-text required-entry validate-email" value="Username" onfocus="javascript:if(this.value=='Username') this.value=''" onblur="if(this.value=='') this.value='Username'"/>
                                    </li>
                                    <li>
                                        <input type="text" name="password" id="password" title="Sign up for our newsletter" class="input-text required-entry validate-email" value="Password" onfocus="javascript:if(this.value=='Password') this.value=''" onblur="if(this.value=='') this.value='Password'"/>
                                    </li>
                                    <li class="control">
                                        <input type="checkbox" name="is_subscribed" title="" value="1" id="is_subscribed" class="checkbox"/>
                                        <label for="is_subscribed">Remember me next time </label>
                                    </li>
                                    <li>
                                        <button type="submit" title="" class="button"></button>
                                    </li>
                                    <li>
                                        <a href="#">Forgotten Your Password?</a>
                                    </li>
                                </ul>
                            </fieldset>
                        </form>
                    </div>
                </li>
                <li class="register"><a href="#">Đăng ký</a></li>
            </ul> 
            <div class="menu">
                <ul>
                    <li class="home active"><a href="#">Trang chủ</a></li>
                    <li><a href="#">Hội thảo</a></li>
                    <li><a href="#">Giải trí</a></li>
                    <li><a href="#">Hội chợ / Triển lãm</a></li>
                    <li><a href="#">Hoạt động xã hội</a></li>
                    <li><a href="#">Du lịch</a></li>
                    <li><a href="#">Văn hóa</a></li>
                    <li><a href="#">Đào tạo</a></li>
                    <li><a href="#">Sự kiện khác</a></li>
                </ul>
            </div>
         </div>
    </div>
    <div id="navigation">
        <div class="center">
            <h2 class="titel">SỰ KIỆN NỔI BẬT</h2>
            <ul class="news">
                <li>
                    <a href="#"><img src="img/img-new.png" alt="Image"  /></a>
                    <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                    <p>Ngày 2 tháng 7 năm 2012</p>
                    <span class="free"> </span>
                </li>
                <li>
                    <a href="#"><img src="img/img-new1.png" alt="Image"  /></a>
                    <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                    <p>Ngày 2 tháng 7 năm 2012</p>
                </li>
                <li>
                    <a href="#"><img src="img/img-new2.png" alt="Image"  /></a>
                    <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                    <p>Ngày 2 tháng 7 năm 2012</p>
                </li>
                <li>
                    <a href="#"><img src="img/img-new3.png" alt="Image"  /></a>
                    <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                    <p>Ngày 2 tháng 7 năm 2012</p>
                     <span class="free"> </span>
                </li>
                <li>
                    <a href="#"><img src="img/img-new4.png" alt="Image"  /></a>
                    <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                    <p>Ngày 2 tháng 7 năm 2012</p>
                </li>
                <li class="last">
                    <img src="img/img-new5.png" alt="Image"  />
                    <button class="button" title="" type="submit"></button>
                </li>
            </ul>
         </div>
    </div>
    <div id="content">
        <div class="center">
            <div class="content-left">
                <div class="today-new">
                    <h2 class="titel">Hôm nay có</h2>
                    <ul class="new-content">
                        <li>
                            <a href="#"><img src="img/img1.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                        <li>
                            <a href="#"><img src="img/img2.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                        <li>
                            <a href="#"><img src="img/img3.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                        <li class="last">
                            <a href="#"><img src="img/img4.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                    </ul>
                </div>
                <div class="event-new">
                    <h2 class="titel">Sự kiện được quan tâm</h2>
                    <ul class="new-content">
                        <li>
                            <a href="#"><img src="img/img5.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                        <li>
                            <a href="#"><img src="img/img6.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                        <li>
                            <a href="#"><img src="img/img7.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                        <li class="last">
                            <a href="#"><img src="img/img8.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                    </ul>
                </div> 
                <div class="new-img">
                    <a href="#"><img src="img/new-img-03.png" alt="Image"  /></a>
                </div> 
            </div>
            <div class="content-right">
                <div class="update-new">
                    <h2 class="titel">Mới cập nhật</h2>
                    <ul class="new-content">
                        <li>
                            <a href="#"><img src="img/img1.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                            <span class="free-1"> </span>
                        </li>
                        <li>
                            <a href="#"><img src="img/img4.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                        <li>
                            <a href="#"><img src="img/img6.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                        </li>
                        <li class="last">
                            <a href="#"><img src="img/img2.png" alt="Image"  /></a>
                            <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                            <p>Ngày 2 tháng 7 năm 2012</p>
                            <span class="free-1"> </span>
                        </li>
                    </ul>
                </div>
                <div class="new-img">
                    <a href="#"><img src="img/new-img-01.png" alt="Image"  /></a>
                    <a href="#"><img src="img/new-img-02.png" alt="Image"  /></a>
                    <a href="#"><img src="img/new-img-04.png" alt="Image"  /></a>
                </div>
            </div>
            <div class="content-center">
                <h2 class="titel">SỰ KIỆN SẮP DIỄN RA</h2>
                <ul class="events-new">
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img9.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                        <span class="free">
                        </span>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img10.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                        <span class="free">
                        </span>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img11.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img12.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                        <span class="free">
                        </span>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img13.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img14.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img15.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                        <span class="free">
                        </span>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img16.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img16.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                    </li>
                    <li>
                        <div class="new-events-img">
                            <a href="#"><img src="img/img16.png" alt="Image"  /></a>
                            <p><span>26</span><span>07</span><span class="last">2012</span></p>
                        </div>
                        <h3><a href="#">CHƯƠNG TRÌNH HỘI THẢO FACE TO FACE</a></h3>
                        <p><span>Hội thảo</span><a href="#"></a><strong>50</strong><span>Lượt xem</span><strong class="views">30</strong></p>
                        <p><span>Số 19 Ba Đình Hà Nội</span></p>
                    </li>
                    <li>
                    <div class="paging">
                        <span>1</span>
                        <span>-</span>
                        <span>8</span>
                        <a class="prev" href="#"></a>
                        <a class="next" href="#"></a>
                    </div>
                    </li>
                </ul>
                
            </div>
        </div>
    </div>
    <div id="footer">
        <div class="center">
            <div class="footer-left">
                <p><strong>© 2012</strong><a href="#">Thamgia.net</a></p>
                <p><span>VP Đà Nẵng: 179 Lê Thanh Nghị Q.Hải Châu</span><span>VP TP.Hồ Chí Minh: Tầng 16 Saigon Tower. 29 Le Duan, Q1</span></p>
                <p><span>ĐT: +84 8 352 7789 </span><span>Email: <a href="#">info@thamgia.net</a></span><span>Hotline: 098 568 4772</span></p>
            </div>
            <div class="footer-right">
                <ul>
                    <li><a href="#">Giới Thiệu</a></li>
                    <li><a href="#">Hướng dẫn</a></li>
                    <li><a href="#">Đối tác</a></li>
                    <li class="last"><a href="#">Liên hệ</a></li>
                </ul>
            </div>
         </div>
    </div>
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
</body>