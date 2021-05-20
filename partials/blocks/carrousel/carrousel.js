(function($) {

	$( document ).ready(function() {
		var owl = $(".carrousel.owl-carousel");
		owl.owlCarousel({
			loop:true,
			nav : false,
			dots : true,
			autoplay:true,
			autoplayTimeout:5000,
			autoplaySpeed:2000,
			autoplayHoverPause:false,
			items: 1,
			checkVisible: false
		});

		var titreDejaAjoute=false;
		owl.on('changed.owl.carousel', function(event) {
			if(titreDejaAjoute) {
				return;
			}
			var dots= $('.owl-dot');
			$.each(dots, function (indexInArray, valueOfElement) { 
				$(valueOfElement).attr('title','Afficher la slide '+(indexInArray+1));
			});
			titreDejaAjoute=true;
		})

	}); //fin document ready
})( jQuery );