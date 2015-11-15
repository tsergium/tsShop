Cufon.replace('.cufon',{color: '-linear-gradient(#3399ff, #2A2865)'});
function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};


jQuery(document).ready(function() {
	// Begin: Product details product gallery
	function switchImage(imageHref, currentBigImage, currentThumbSrc) {
		var theBigImage = $('#bigpic img');
		if (imageHref != currentBigImage) {
			theBigImage.fadeOut(500, function(){
				theBigImage.attr('src', imageHref).fadeIn(250);
			});
		}
	}
	$('.thumbs-prod ul li a').click(function() {
		var currentBigImage = $('#bigpic img').attr('src');
		var newBigImage = $(this).attr('id');
		switchImage(newBigImage, currentBigImage, newBigImage);
	});
	// End: Product details product gallery
	
//	$("#slider").easySlider({orientation:'vertical'});
	$("#slider").easySlider({
//		vertical: true
	});
	
    jQuery('#mycarousel').jcarousel({
        auto: 2,
		scroll: 2,
        wrap: 'last',
		animation: 'slow',
        initCallback: mycarousel_initCallback
    });

	$('.info').each(function() {
		$(this).fancybox({
			'hideOnOverlayClick': false,
			'hideOnContentClick': false,
			'showCloseButton'	: true,
			'titleShow'			: true,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'overlayOpacity'	: 0.3,
			'overlayColor'		: '#99D1E9',
			'autoDimensions'	: false,
			'width'				: 500,
			'height'			: 480,
			'onComplete'		: function() { $('#fancybox-title').css({'top':'-25px', 'margin':'0px auto', 'bottom':'auto'}) }
		});
	});
});