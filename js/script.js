//Slick Carousel (Events Slider)
jQuery('#myCarousel').slick({
	slidesToShow: 3,
	slidesToScroll: 1,
	autoplay: false,
	slide: '.carousel-card',
	appendArrows: '#myCarousel',
	prevArrow: '<i class="fa fa-chevron-left prev-arrow" aria-hidden="true"></i>',
	nextArrow: '<i class="fa fa-chevron-right next-arrow" aria-hidden="true"></i>',

	responsive: [{

		breakpoint: 992,
		settings: {
		  slidesToShow: 2,
		  infinite: true
		}
	},
	{ breakpoint: 768,
	  settings: {
		slidesToShow: 1,
		infinite: true  
	  }
	}
	]
});

//Equal heights for slider cards
jQuery('#myCarousel').on('setPosition', function () {
	jQuery(this).find('.slick-slide').height('auto');
	var slickTrack = jQuery(this).find('.slick-track');
	var slickTrackHeight = jQuery(slickTrack).height();
	jQuery(this).find('.slick-slide').css('height', slickTrackHeight + 'px');
});

//Datepicker (Calendar)
jQuery('.input-daterange').datepicker({
    format: "yyyy-mm-dd",
    startDate: "0 days",
    maxViewMode: 0,
    todayBtn: true,
    forceParse: false,
    todayHighlight: true
});