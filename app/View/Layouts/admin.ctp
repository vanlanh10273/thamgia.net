<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>Mạng chia sẻ sự kiện cộng đồng, hội thảo, khóa đào tạo thamgia.net</title>
  
    <meta name="description" content="thamgia.net - Mạng chia sẻ sự kiện, hội thảo, khóa đào tạo, triển lãm, khai trương và sự kiện cộng đồng khác diễn ra tại các thành phố lớn. Hòa mạng tham gia, mở ra cơ hội." />
    <meta name="keywords" content="sự kiện, hội thảo, triển lãm, khai trương, khánh thành, sự kiện Đà Nẵng, su kien Da nang, hội thảo Đà Nẵng, hoi thao Da nang, su kien Ha noi, sự kiện Hà nội, hội thảo Hà nội, hội thảo tp HCM, chia se su kien, dien gia, diễn giả, nguoi khong lo, người khổng lồ, hội thảo du học, hoi thao du học, tham gia" />
    <?php 
        echo $this->Html->css('layout');  
        
        echo $this->Html->css('validationEngine.jquery');
        echo $this->Html->css('validationEngine-day');
        echo $this->Html->css('jquery-ui-timepicker-addon');
        
    ?>
    <!--[if lt IE 9]>
    <?php echo $this->Html->css('ie'); ?>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <?php
        echo $this->Html->script('jquery.min.js');
        echo $this->Html->script('jquery.tablesorter.min.js');
        echo $this->Html->script('jquery.equalHeight.js');
        echo $this->Html->script('jquery.ui.core.js');
        echo $this->Html->script('jquery.ui.min.js');
        
        $title = isset($page_title) ? $page_title : '';
        $user = isset($users_name) ? $users_name : '';
    ?>
</head>
<body>
    <header id="header">
        <hgroup>
            <h1 class="site_title"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'approveEvents')); ?>">Website Admin</a></h1>
            <h2 class="section_title"><?php echo $page_title; ?></h2><div class="btn_view_site"><a target="_blank" href="<?php echo $this->Html->url('/'); ?>">View Site</a></div>
        </hgroup>
    </header> <!-- end of header bar -->
    
    <section id="secondary_bar">
        <div class="user">
            <p><?php echo $user; ?></p>
            <a class="logout_user" href="<?php echo $this->Html->url(array('controller'=>'Users', 'action' => 'logout')); ?>" title="Logout">Logout</a>
        </div>
        <div class="breadcrumbs_container">
            <article class="breadcrumbs"><a href="<?php echo $this->Html->url('/').'admin'; ?>">Website Admin</a> <div class="breadcrumb_divider"></div> <a class="current"><?php echo $title; ?></a></article>
        </div>
    </section><!-- end of secondary bar -->
    <aside id="sidebar" class="column">
        <h3>Sự Kiện</h3>
        <ul class="toggle">
            <li class="icn_categories"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'approveEvents')); ?>">Duyệt Sự Kiện</a></li>
            <li class="icn_tags"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'highlights')); ?>">Sự Kiện Nổi Bật</a></li>
            <li class="icn_photo"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'advertisements')); ?>">Quảng Cáo</a></li>
            <li class="icn_folder"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'events')); ?>">Sự kiện đã duyệt</a></li>
            <li class="icn_photo"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'dailyCoupon')); ?>">Daily Coupon</a></li>
        </ul>
        <h3>Users</h3>
        <ul class="toggle">
            <li class="icn_add_user"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addUser')); ?>">Add New User</a></li>
            <li class="icn_view_users"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'users')); ?>">Users</a></li>
            <li class="icn_folder"><a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'sendEmails')); ?>">Emails</a></li>
        </ul>
        <h3>Emails</h3>
        <ul class="toggle">
            <li class="icn_add_user"><a href="<?php echo $this->Html->url(array('controller' => 'AccountEmails', 'action' => 'index')); ?>">Tài khoản email</a></li>
            <li class="icn_view_users"><a href="<?php echo $this->Html->url(array('controller' => 'AccountEmails', 'action' => 'inactive')); ?>">Email lỗi</a></li>
        </ul>
        
        <footer>
            <hr />
            <p><strong>Copyright &copy; 2012 Thamgia.net</strong></p>
        </footer>
    </aside><!-- end of sidebar -->
    <section id="main" class="column">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>    
    </section>
    <?php //echo $this->element('sql_dump'); ?>
</body>