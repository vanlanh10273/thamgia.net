<?php
    $cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title><?php echo $title; ?></title>
    <meta name="description" content="<?php echo $meta_description; ?>" />
    <meta name="keywords" content="<?php echo $meta_keywords; ?>" />
    
    <!--<meta property="og:title" content="<?php echo $title; ?>"/>
    <meta property="og:image" content="<?php echo $this->Html->url('/') . NO_IMG_URL;  ?>"/>
    <meta property="og:site_name" content="thamgia.net"/>
    <meta property="og:description" content=""/>-->
    <!--<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.8.21/themes/ui-lightness/jquery-ui.css" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>-->
    <?php
        echo $this->Html->meta('icon');
   
        /*echo $this->Html->css('common-old');
        
        echo $this->Html->css('validationEngine.jquery');
        echo $this->Html->css('validationEngine-day');
        echo $this->Html->css('jquery-fancybox');
        echo $this->Html->css('messageBox');
        echo $this->Html->css('tipped');
        echo $this->Html->css('jquery-ui-timepicker-addon');
        echo $this->Html->css('jquery-bubble-popup-v3');*/
        //New temp
        echo $this->Html->css('font-awesome.min');
        echo $this->Html->css('colorbox');
        echo $this->Html->css('datepicker');
        echo $this->Html->css('common');
        //echo $this->Html->css('cake.generic');
        
        /*echo $this->Html->script('jquery-1.7.1.min');
        echo $this->Html->script('cufon-yui');
        echo $this->Html->script('UTM_Nokia_StandardB_700.font');
        echo $this->Html->script('UTM_NOKIA_STANDARD_400.font');
        echo $this->Html->script('jquery.ui.core');
        echo $this->Html->script('jquery.ui.min.js');
        echo $this->Html->script('ui.datepicker');
        echo $this->Html->script('validationEngine');
        echo $this->Html->script('validationEngine-vi');
        echo $this->Html->script('jquery.fancybox-1.3.4.pack');
        echo $this->Html->script('stickyscroll');
        echo $this->Html->script('jquery.tinyscrollbar.min');
        echo $this->Html->script('jquery.jcountdown1.3');
        echo $this->Html->script('tipped');
        echo $this->Html->script('category.cycle');
        echo $this->Html->script('jquery-bubble-popup-v3.min');
        //echo $this->Html->script('equalsHeight');
        echo $this->Html->script('jquery.masonry.min.js');
        echo $this->Html->script('jquery.endless-scroll.js'); 
        //echo $this->Html->script('jquery.totemticker.min.js'); 
        echo $this->Html->script('jcarousellite_1.0.1c4.js'); 
        echo $this->Html->script('common-old');
        echo $this->Html->script('link');
        echo $this->Html->script('oauthpopup');*/
        
        //Script new temp
        echo $this->Html->script('jquery-1.11.0.min');
        echo $this->Html->script('bootstrap.min');
        echo $this->Html->script('bootstrap-datepicker');
        echo $this->Html->script('masonry.pkgd.min');
        echo $this->Html->script('jquery.jcarousellite.min');
        echo $this->Html->script('jquery.colorbox-min');
        echo $this->Html->script('jquery.nicescroll.min');
        echo $this->Html->script('common');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
    ?>
	

</head>