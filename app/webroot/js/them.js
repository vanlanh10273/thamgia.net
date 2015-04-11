$(document).ready(function(){
	var cookieName = 'theme';
	var cookieOptions ={path:'/', expires:3};
	if ($.cookie(cookieName) == null){
		$.cookie(cookieName, 1, cookieOptions);
	}
	$('#wd-theme-switcher').addClass('wd-theme' + $.cookie(cookieName));
	$('#wd-switcher > li').eq($.cookie(cookieName) - 1).addClass('wd-current');
	var totalTheme = $('#wd-switcher > li').length;
	$('#wd-switcher').find('a').each(function(e){
		$(this).click(function(){
			$.cookie(cookieName, $(this).attr('rel'), cookieOptions);
			$('#wd-switcher > li').removeClass('wd-current').eq($.cookie(cookieName) - 1).addClass('wd-current');
			for (var i=1;i<=totalTheme;i++ ){
				$('#wd-theme-switcher').removeClass('wd-theme' + i);
			}
			$('#wd-theme-switcher').addClass('wd-theme' + $.cookie(cookieName));
			return false;
		});
	});
});