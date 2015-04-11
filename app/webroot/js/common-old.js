Cufon.replace('ul.news li h3 a,ul.events-new-list li h3 a,ul.menu-content li a,.mailbox ul li ',{
    fontFamily: 'UTM Nokia StandardB',
    hover: {
        color: '#909090'
        
    }
    });
Cufon.replace('.daily-coupon p a,.complete-thanks p span.thanks,.message-fancybox h3,#loantin .comment .select h2,#loantin .comment .comment-text fieldset label,.management-message .titel,.registered-users-right ul li p span,.table-personal td p strong,.wd-tab .wd-item li a,.wd-tab .no-tab li a,h3.persona-titel,ul.login li a.login, ul.login li.register a, .titel-events,.titel-provinces, .daily-coupon-titel,.main-search p label,#header .search-input .main-search #wd-reservation span,.join-now .join-now-content li p,.join-now .join-now-content li p.day span,.google-wap-left p.text,.voucher h3,.voucher p.day span,span.promotion,.voucher,ul.customer-information-content li.times p,ul.customer-information-content li.times span',{
    fontFamily: 'UTM Nokia StandardB'
    });
Cufon.replace('.personal-information-new-01 ul  li p a ,#footer .footer-left p,#footer .footer-right ul li a ,',{
    fontFamily: 'UTM NOKIA STANDARD',
    hover: {
        color: '#909090'
        
    }
});
Cufon.replace('.complete-thanks p span.info-finish,#loantin .comment .select fieldset label,.management-message .content .members,.table-personal td p span,a.editing,a.send-message,.personal-information-new-01 ul  li p,.invited-participate ul li a,.output p,.events .content-events li a,  .provinces .content-provinces li a,ul.customer-information-content li.information-01 h4,ul.customer-information-content li.thanks p span,  .button-update span',{
    fontFamily: 'UTM NOKIA STANDARD'
});

$(document).ready(function() {
	if (!$.browser.opera) {
        $('select').each(function(){
            var title = $(this).attr('title');
            if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
            $(this)
                .css({'z-index':10,'opacity':0,'-khtml-appearance':'none'})
                .after('<span class="select">' + title + '</span>')
                .change(function(){
                    val = $('option:selected',this).text();
                    $(this).next().text(val);
                    })
        });
    };
    
    $(".pt-button-nav").hover(function () {
       $('.pt-button-nav a').toggleClass("active");
        $('.content-events').stop('true','true').slideToggle(0);
        return false;
    });
	
	$( "#fromdate" ).datepicker({dateFormat: 'dd/mm/yy'});
	$( "#todate" ).datepicker({dateFormat: 'dd/mm/yy'});
	$( "#fromdate1" ).datepicker({dateFormat: 'dd/mm/yy'});
	$( "#todate1" ).datepicker({dateFormat: 'dd/mm/yy'});
	
	
	jQuery('p a.main-search-arrow').click(function(){
		if($(this).parent().hasClass('active')==false){
		   $(this).parent().addClass('active').siblings('p a.wd-search').removeClass('active');
		    $(this).parent().next('.main-search').slideDown();
		     $(this).parent().next().siblings('.main-search').slideUp();}
		else{
		   if($(this).parent().next('.main-search').is(':hidden')==false){
			   $(this).parent().next('.main-search').slideUp();$(this).parent().removeClass('active');}
		else{ 
			$(this).parent().next('.main-search').slideDown();}
			  $(this).parent().next().siblings('.main-search').slideUp();}
			return false;
	});
	
	jQuery("a.participated").fancybox({
		'overlayShow':false,
		'transitionIn':'elastic',
		'transitionOut':'elastic'
	});
    jQuery("a.button-voucher").fancybox({
        'overlayShow':false,
        'transitionIn':'elastic',
        'transitionOut':'elastic'
    });
	jQuery(".update a.fancybox").fancybox({
		'overlayShow':false,
		'transitionIn':'elastic',
		'transitionOut':'elastic'
	});
	jQuery(".reply a.reply-message").fancybox({
		'overlayShow':false,
		'transitionIn':'elastic',
		'transitionOut':'elastic'
	});  
	/*$('.info-use01').stickyScroll({ container: '#content' });*/
    
    jQuery(document).ready(function($) {
          Tipped.create(".output li img");
 
    });  
    /*$('.s7').cycle({ 
            fx:     'fade', 
            speed:  'fast', 
            timeout: 0, 
            next:   '.next3', 
            prev:   '.prev3' 
    });*/
    /*
    $(".events").hover(function () {
       $('.content-event-how').slideToggle();
    });
    $(".provinces").hover(function () {
       $('.content-provinces').slideToggle();
    }); */
    $(".search-input").click(function () {    
      $(".main-search").slideDown();
      
    });
    $('.search-input').bind('click',function(e){e.stopPropagation();$(document).bind('click',function(){$('.main-search').slideUp(500);});});
    
    $(".notification-infox").click(function () {
       $('.notifications').slideToggle();
       $('.new-control').slideUp();
    });
    $('.notification-infox').bind('click',function(e){e.stopPropagation();$(document).bind('click',function(){$('.notifications').slideUp(500);});});
    
    $(".login").click(function () {
       $('.new-control').slideToggle();
        $('.notifications').slideUp();
    });
    
    
    
    
});