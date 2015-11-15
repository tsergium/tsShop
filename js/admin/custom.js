// CAROUSEL
Cufon.replace('.cufon', {
	color: '-linear-gradient(#3399ff, #2A2865)'
});
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
	$('.thumbs-prod ul li a').click(function() {
		var currentBigImage = $('#bigpic img').attr('src');
		var newBigImage = $(this).attr('id');
//		var currentThumbSrc = $(this).attr('id');
		switchImage(newBigImage, currentBigImage, newBigImage);
	});

	function switchImage(imageHref, currentBigImage, currentThumbSrc) {
		var theBigImage = $('#bigpic img');
		if (imageHref != currentBigImage) {
			theBigImage.fadeOut(500, function(){
				theBigImage.attr('src', imageHref).fadeIn(250);
//				var newImageDesc = $("#thumbs ul li a img[src='"+currentThumbSrc+"']").attr('alt');
//				$('p#desc').empty().html(newImageDesc);
			});
		}
	}
	// End: Product details product gallery
	
	$("#slider").easySlider({
		orientation:'vertical'
	});
	
    jQuery('#mycarousel').jcarousel({
        auto: 2,
		scroll: 2,
        wrap: 'last',
		animation: 'slow',
        initCallback: mycarousel_initCallback
    });
});


/* TABS (details for products) */
$(document).ready(function() {
	
	//Default Action
	$(".prod-tabs-content").hide(); //Hide all content
	$("ul.prod-tabs li:first").addClass("active").show(); //Activate first tab
	$(".prod-tabs-content:first").show(); //Show first tab content

	//On Click Event
	$("ul.prod-tabs li").click(function() {
		$("ul.prod-tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".prod-tabs-content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});

});

//Dropdown menu
$(document).ready(function() {
	function megaHoverOver(){
		$(this).find(".sub").stop().fadeTo('fast', 1).show();

		//Calculate width of all ul's
		(function($) {
			jQuery.fn.calcSubWidth = function() {
				rowWidth = 0;
				//Calculate row
				$(this).find("ul").each(function() {
					rowWidth += $(this).width();
				});
			};
		})(jQuery);

		if ( $(this).find(".row").length > 0 ) { //If row exists...
			var biggestRow = 0;
			//Calculate each row
			$(this).find(".row").each(function() {
				$(this).calcSubWidth();
				//Find biggest row
				if(rowWidth > biggestRow) {
					biggestRow = rowWidth;
				}
			});
			//Set width
			$(this).find(".sub").css({'width' :biggestRow});
			$(this).find(".row:last").css({'margin':'0'});

		} else { //If row does not exist...

			$(this).calcSubWidth();
			//Set Width
			$(this).find(".sub").css({'width' : 938});

		}
	}

	function megaHoverOut(){
	  $(this).find(".sub").stop().fadeTo('fast', 0, function() {
		  $(this).hide();
	  });
	}

	var config = {
		 sensitivity: 2, // number = sensitivity threshold (must be 1 or higher)
		 interval: 100, // number = milliseconds for onMouseOver polling interval
		 over: megaHoverOver, // function = onMouseOver callback (REQUIRED)
		 timeout: 200, // number = milliseconds delay before onMouseOut
		 out: megaHoverOut // function = onMouseOut callback (REQUIRED)
	};
});