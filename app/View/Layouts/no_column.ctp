<?php echo $this->element('thamgia/layout_header'); ?>
<body>
	<?php echo $this->element('thamgia/header'); ?>
    <?php echo $this->element('thamgia/navigation'); ?>
    <?php echo $this->element('thamgia/daily_coupon'); ?>
	<div id="content">
        <div class="center">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content'); ?>
        </div>
	</div>
	<?php echo $this->element('thamgia/footer'); ?>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
