$(document).ready(function(){
	/*------- Menu -------------*/
	$('.icon-menu-link').click (function (){
		$('#pt-left-thamgia').toggleClass('pt-left-thamgia-active');
		$('#pt-wrapper').toggleClass('pt-wrapper-active');
		$('body').toggleClass('pt-body-active');
		$(this).toggleClass('icon-menu-link-active');
		
	});

	/*-------Datepicker -------------*/
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	var checkin = $('#dpd1,#dpd11').datepicker({
		 onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		if (ev.date.valueOf() > checkout.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			checkout.setValue(newDate);
		}
		checkin.hide();
		$('#dpd2,#dpd22')[0].focus();
	}).data('datepicker');
	var checkout = $('#dpd2,#dpd22').datepicker({
		onRender: function(date) {
			return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		checkout.hide();
	}).data('datepicker');


	/*------- Address -------------*/
	$('.pt-address').hover (function (){
		$('.pt-list-address').toggleClass('pt-list-address-active');
	});

	/*------- Address -------------*/
	$('.list-sup-menu').hover (function (){
		$('.pt-list-menu-content-sup').toggleClass('pt-list-menu-content-sup-active');
	});
	
	/*------- Address -------------*/
	$('.pt-search .input-text').click (function (){
		$('.pt-block-content-search').addClass('pt-block-content-search-active');
	});
	$('.pt-search .button-search').click (function (){
		$('.pt-block-content-search').removeClass('pt-block-content-search-active');
	});
	/*------- Masonry -------------*/
	var container = document.querySelector('.pt-upcoming-events-content');
	var msnry = new Masonry( container, {
	  // options
	  itemSelector: '.item'
	});

	/*------- jCarouselLite -------------*/
	$(".custom-container .carousel").jCarouselLite({
		visible: 7,
		circular: false,
        btnNext: ".custom-container .next",
        btnPrev: ".custom-container .prev"
    });

	/*------- Select -------------*/
    $('.pt-select select').each(function(){
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

    /*------- Colorbox -------------*/
    $(".block-user .friend").colorbox({inline:true});
    $(".link-login").colorbox({inline:true});
    $(".link-sigup-popup a").colorbox({inline:true});
    $(".pt-message").colorbox({inline:true});
    $(".pt-times-detail .send").colorbox({inline:true});
   
    /*------- NiceScroll -------------*/
    $(".pt-info-content-stt").niceScroll(".pt-info-content-stt .pt-niceScroll",{boxzoom:true}); 
});	
	
	
