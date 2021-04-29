(function($) {

	$( document ).ready(function() {
		var width=$(window).width();

		/****************** Modaal*************************/
		//TODO : supprimer si jamais utilisé + enlever enqueue dans functions.php
		$('.ouvrir-modaal').modaal();
		$('.fermer-modaal').click(function(){
			$('.ouvrir-modaal').modaal('close');
		});
		$('.video-modaal').modaal({
			type: 'video',
			height:500,
			width:900
		});

		
		
		/*********Afficher/masquer le volet de recherche **********/

		//Au chargement de la page, masquer les volets de recherche
		$('.volet-recherche').css('width',0);
		$('.volet-recherche').hide();

		var boutonRecherche=$("button.recherche");
		if($(boutonRecherche).length>0) {
			$(boutonRecherche).click(function(){
				var voletRecherche=$(this).attr('aria-controls');
				if($(boutonRecherche).attr('aria-expanded')=="false") {
					$(voletRecherche).show();
					$(voletRecherche).css('width','200px');
					$(voletRecherche).css('flex-grow','1');
					$(voletRecherche).attr('aria-expanded','true');
					$(boutonRecherche).attr('aria-expanded','true');
					$('#volet-recherche .search-field').focus();
					$('.nav-main .centreur:first-of-type').hide();
					
				} else {
					$(voletRecherche).css('width','0');
					$(voletRecherche).css('flex-grow','unset');
					$(voletRecherche).fadeOut();
					$(voletRecherche).attr('aria-expanded','false');
					$(boutonRecherche).attr('aria-expanded','false');
					$('.nav-main .centreur:first-of-type').show();
				}

			});
		}
		
		/********* Ouvrir-fermer les sous-menus mobile **********/
		var ouvrirSousMenu=$('.volet-navigation .ouvrir-sous-menu');
		if(ouvrirSousMenu.length>0) {
			ouvrirSousMenu.click(function(e) {
				var sousMenu=$(this).siblings('.sub-menu');

				if($(this).hasClass('js-ouvert')) {
					//le sous-menu était ouvert, on le referme
					$(this).removeClass('js-ouvert');
					$(sousMenu).slideUp();
				} else {
					//on referme tous les sous-menus
					ouvrirSousMenu.removeClass('js-ouvert');
					$('.volet-navigation .sub-menu').slideUp();

					//on ouvre celui demandé
					$(this).addClass('js-ouvert');
					$(sousMenu).slideDown();
				}
			});
		}
		/********* Desktop : neutraliser clic pour lien de menu parent **********/
		/*
		var liensParents=$('.volet-navigation .menu-item-has-children > a');
		if(width>=960 && liensParents.length>0) {
			liensParents.click(function(e) {
				e.preventDefault();
				$(this).blur();
			})
		}*/


		/****************** Sticky header *************************/
		
		var siteHeader=$('.site-header');
		var siteContent=$('.site-main');
		var mainNavigation=$('.nav-main');
		var topbar=$('.topbar');
		
		mainNavigationTop=mainNavigation.offset().top;

		
		if(width>=960) {
			$(window).scroll(function () { // scroll event
				var windowTop = $(window).scrollTop(); // returns number
				if (windowTop > mainNavigationTop ) {
					siteContent.css('margin-top',mainNavigation.outerHeight());
					siteHeader.addClass('js-sticky');
					mainNavigation.addClass('js-sticky');
				} else {
					siteContent.css('margin-top',0);
					siteHeader.removeClass('js-sticky');
					mainNavigation.removeClass('js-sticky');
				}

			});
		} else {
			$(topbar).addClass('js-sticky');
			siteHeader.css('margin-top',topbar.outerHeight());
		}

		/****************** Carrousel de logos adhérents *************************/
		//TODO : supprimer si jamais utilisé + enlever enqueue dans functions.php


		$(".acf-block-adherents-slider .owl-carousel").owlCarousel({
			loop:true,
			nav : false,
			dots : false,
			margin : 44,
			autoplay:true,
			autoplayTimeout:2000,
			autoplaySpeed:2000,
			autoplayHoverPause:true,
			responsive : {
				// breakpoint from 0 up
				0 : {
					items:2,
				},
				500 : {
					items : 3,
				},
				740 : {
					items : 4,
				},
				926 : {
					items : 5,
				},
				1120 : {
					items : 6,
				},
				1320 : {
					items : 7,
				},
				1510 : {
					items : 8,
				},
				1702 : {
					items : 9,
				},
				1900 : {
					items : 10,
				}
			},
		});


		/****************** Filtre articles et producteurs *************************/	
		if($("#filtre-liste").length>0) {

			var page=parseInt($('#liste-filtrable').attr('data-pagination'));
			if(typeof(page)===NaN || page <=0) {
				page=8;
			}
			console.log(page);
			var optionsListe = {
				valueNames: ['term'],
				page: page, 
				pagination: true
			};
	
			var listeFiltrable = new List('liste-filtrable', optionsListe);

			var resultats=$('.list, .pagination');
			
			$('#filtre-liste').change(function(){
				//quand on clique sur une checkbox
				$(resultats).animate(
					{opacity:0},
					400,
					'linear',
					function(){
						//callback de l'animation
						//on récupère le type sélectionné
						var selectedValue=$("#filtre-liste input:checked").val();

						if(selectedValue=='tous') {
							//on réinitialise le filtre
							listeFiltrable.filter();
						} else {
							//on filtre la liste pour ne garder que les éléments dont le type est sélectionné
							listeFiltrable.filter(function(item) {
								return (selectedValue==item.values().term);
							});
						}
						//la nouvelle liste est prête, nouvelle animation pour réafficher
						$(resultats).animate(
							{opacity:1}, 1000, 'linear'	
						);
					}
				);
				
			});
		}

		/*********Filtrer les ressources si paramètre videos ds l'url **********/
		var url_string = window.location.href;
		var url = new URL(url_string);
		filtreRessources = url.searchParams.get("filtre_ressources");
		if(filtreRessources=="videos" && $('#filtre-liste.filtre-ressources').length>0) {
			$('#filtre-liste input').prop( "checked", false );
			$('#videos').prop( "checked", true );
			$('#filtre-liste').trigger("change");
		}


		/*=================================================
		CART PAGE : changer la quantité avec les boutons + et - puis mettre à jour
		=================================================*/

		var $cart_shop_table = $('table.shop_table.cart');
		if($cart_shop_table.length>0) {
			var timeout;

			kasutan_bind_qty(); // on lie les évènements dès le chargement de la page

			$( document.body ).on( 'updated_wc_div', function(){
				kasutan_bind_qty(); // on recommence après mise à jour du panier
			});
		}
		function kasutan_bind_qty() {

			//Modifier quantité au clic sur un bouton +/-
			$('.change-quantity').click(function(e){
				e.preventDefault();
				var action=$(this).attr("data-value");
				var input=$(this).parent('.quantity').find('input.qty');
				var currentQty=parseInt($(input).val());
				var minQty=parseInt($(input).attr('min'));
				var maxQty=parseInt($(input).attr('max'));
				if(currentQty>minQty && action=="-") {
					$(input).val(currentQty-1);
					kasutan_update_cart();
				} else if (currentQty<maxQty && action=="+") {
					$(input).val(currentQty+1);
					kasutan_update_cart();
				}
			});

			$cart_shop_table.on('change keyup mouseup', 'input.qty', function(){ 
				kasutan_update_cart();
			});
		}

		function kasutan_update_cart() {
			//console.log('womoon update cart');
			if (timeout != undefined) clearTimeout(timeout); //cancel previously scheduled event
			timeout = setTimeout(function() {
				$('button[name="update_cart"]').prop("disabled", false);
				$('button[name="update_cart"]').trigger('click');
			}, 1000 );
		}

		/*=================================================
		PRODUCT PAGE : changer la quantité avec les boutons + et -
		=================================================*/
		var singleProductButton=$('.single-product .summary .single_add_to_cart_button');
		if(singleProductButton.length>0) {
			kasutan_bind_qty();
		}

		

	}); //fin document ready
})( jQuery );

