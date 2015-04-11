<?php echo $this->element('thamgia/layout_header'); ?>
<body>
    <?php //echo $this->element('thamgia/header'); ?>
    <?php //echo $this->element('thamgia/navigation'); ?>
    <?php //echo $this->element('thamgia/daily_coupon'); ?>
    <!--
    <div id="content">
        <div class="center">
            <div class="content-right">
                <?php 
                    //echo $this->element('thamgia/new_member');
                ?>
                <?php 
                    //echo $this->element('thamgia/events_today'); 
                ?>
                <?php 
                    //echo $this->element('thamgia/activities'); 
                ?>
                <?php 
                    //echo $this->element('thamgia/advertisements',
                    //array(
                   //     'side_id' => RIGHT_SIDE
                   // )); 
                ?>
                
                <?php 
                    //echo $this->element('thamgia/social_network');
                ?>
            </div>
            <div class="content-center">
                <?php //echo $this->Session->flash(); ?>
                <?php //echo $this->fetch('content'); ?>
            </div>
        </div>
    </div> -->
    <?php //echo $this->element('thamgia/footer'); ?>
    <?php echo $this->element('thamgia/left-sidebar'); ?>
    <div id="pt-wrapper">
        <?php echo $this->element('thamgia/new-header'); ?>
        <div id="pt-content-container">
			<div class="container-fluid">
                <?php echo $this->element('thamgia/menu'); ?>
            </div>
        </div>
        <?php echo $this->element('thamgia/new-footer'); ?>
    </div>
    <?php //echo $this->element('sql_dump'); ?>
</body>