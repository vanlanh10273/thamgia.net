<?php echo $this->element('thamgia/layout_header'); ?>
<body>
	<?php echo $this->element('thamgia/header'); ?>
    <?php echo $this->element('thamgia/navigation'); ?>
    <?php echo $this->element('thamgia/daily_coupon'); ?>
                                                        
	<div id="content" class="about">
        <div class="center">
            <div class="content-center">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
	</div>
	<?php echo $this->element('thamgia/footer'); ?>
	
</body>
</html>
