$( document ).ready(function() {
	var width=$(window).width();
	if(width>=1000) {
		var pas=$('.acf-visuels-2 figure').outerWidth(true);
		
		$('.acf-visuels-2').each( function (index, element) { 
			decaleImages(0,element);
		});

		$('.acf-visuels-2 button').click(function (e) { 
			var bloc=$(this).parent('.acf-visuels-2');
			var courant=parseInt($(bloc).attr('data-courant'));
			if($(this).hasClass('suivant')) {
				decaleImages(courant+1,bloc);
			} else if(courant>0) {
				decaleImages(courant-1,bloc);
			}
		});
		

		function decaleImages(i,bloc) {

			var total=$(bloc).find('figure').length;
			var images=$(bloc).find('.images');
			var precedent=$(bloc).find('button.precedent');
			var suivant=$(bloc).find('button.suivant');

			if(0 <= i < total) {
				$(images).animate({
					'left': -1*i*pas
				},500);
				$(bloc).attr('data-courant', i);
			}
			if(i===0) {
				precedent.fadeOut('slow');
				suivant.fadeIn('slow');
			} else if(i<total-2) {
				precedent.fadeIn('slow');
				suivant.fadeIn('slow');
			} else {
				suivant.fadeOut('slow');
			}
		}
	}

});