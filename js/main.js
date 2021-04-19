(function($) {

	$( document ).ready(function() {
		var width=$(window).width();
		/****************** Modaal*************************/
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
		var voletNavigation=$('#volet-navigation');
		function fermeMenuMobile(){
			if($(voletNavigation).hasClass('toggled')) {
				$(voletNavigation).removeClass('toggled');
				$(voletNavigation).attr('aria-expanded',false);
				$('.menu-toggle').attr('aria-expanded',false);
			}
		}

		var voletRecherche=$('#volet-recherche');
		if($(voletRecherche).length>0) {
			var boutonRecherche=$("#ouvrir-recherche");
			$(boutonRecherche).click(function(){
				if($(boutonRecherche).attr('aria-expanded')=="false") {
					$(voletRecherche).fadeIn('slow');
					$(voletRecherche).css('display','flex');
					$(voletRecherche).attr('aria-expanded','true');
					$(boutonRecherche).attr('aria-expanded','true');
					$('#volet-recherche .search-field').focus();

					$('body,html').animate(
						{scrollTop : 0},
						400,
						function() {
							fermeMenuMobile();
						}
					);
					
				} else {
					$(voletRecherche).fadeOut('slow');
					$(voletRecherche).attr('aria-expanded','false');
					$(boutonRecherche).attr('aria-expanded','false');
				}

			});
			$('#fermer-recherche').click(function(){
				$(voletRecherche).fadeOut('slow');
				$(voletRecherche).attr('aria-expanded','false');
				$(boutonRecherche).attr('aria-expanded','false');
			});
		}
		
		/********* Ouvrir-fermer les sous-menus mobile **********/
		var ouvrirSousMenu=$('.ouvrir-sous-menu, .menu-item-has-children > a');
		if(width<960 && ouvrirSousMenu.length>0) {
			ouvrirSousMenu.click(function(e) {
				e.preventDefault();
				$(this).siblings('.sub-menu').animate(
					{right:0},
					400
				);
			});
			$('.fermer-sous-menu').click(function(){
				$(this).parents('.sub-menu').animate(
					{right:-1*width},
					400
				);
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
		var pageHeaderImg=$('.entry-header .image');
		var siteContent=$('.site-main');
		var mainNavigation=$('.main-navigation');
		var headerTop=0;
		var headerBottom=0;
		updateHeaderPosition();


		$(window).scroll(function () { // scroll event
			var windowTop = $(window).scrollTop(); // returns number
			var windowBottom=window.innerHeight+windowTop;
			if (windowTop > headerTop ) {
				siteContent.css('margin-top',siteHeader.outerHeight());
				siteHeader.addClass('sticky');
				mainNavigation.addClass('sticky');
				var parallax=windowTop*0.2;
				if(parallax <= 40) {
					$(pageHeaderImg).css({'transform':'translateY(-'+parallax+'px)'});
				}
			} else {
				siteContent.css('margin-top',0);
				siteHeader.removeClass('sticky');
				mainNavigation.removeClass('sticky');
			}

		});
		

		//Si on permet au visiteur de masquer la topbar
		//var topbar = $('.topbar');
		//updateHeaderPosition()

		function updateHeaderPosition() {
			headerTop=siteHeader.offset().top;
			headerBottom=headerTop + siteHeader.outerHeight(); // inclut la topbar si elle est présente
			document.documentElement.style.setProperty('--header-bottom', headerBottom); //utile pour positionner le menu mobile
		}



		/****************** Carrousel de logos adhérents *************************/


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


		/****************** Filtre agenda et ressources *************************/	
		if($("#filtre-liste").length>0) {
			var boutonPlus=("#afficher-plus");
			var increment=1000; // au cas où il n'y aurait pas de bouton plus avec data-increment stocké
			if($(boutonPlus).length >0) {
				increment=parseInt($(boutonPlus).attr('data-increment'));
			}
			var resultats=('.list');
			var listeFiltrable = new List('liste-filtrable', {
				valueNames: ['type'],
				page: increment,
				pagination: true
			});
			$('#filtre-liste').change(function(){
				//quand on clique sur une checkbox
				$(resultats).animate(
					{opacity:0},
					400,
					'linear',
					function(){
						//callback de l'animation
						var selectedValues=[];
						//on crée un tableau avec tous les types cochés
						$("#filtre-liste input:checked").each(function(i) {
							selectedValues.push($(this).val());
						});
						//on filtre la liste pour ne garder que les éléments dont le type est présent dans la liste
						listeFiltrable.filter(function(item) {
							return (selectedValues.indexOf(item.values().type)>=0);
						});
						actualiseBouton();
						//la nouvelle liste est prête, nouvelle animation pour réafficher
						$(resultats).animate(
							{opacity:1}, 1000, 'linear'	
						);
					}
				);
				
			});
			$(boutonPlus).click(function(){
				//calcul du nouveau nombre d'évènements à afficher
				var affiche=parseInt($(this).attr('data-affiche'));
				var next=affiche + increment;
				var mettreFocus=affiche+1;

				$(resultats).animate(
					{opacity:0},
					400,
					'linear',
					function(){
						//callback de la première animation
						//on applique à la liste
						listeFiltrable.show(0,next);
						actualiseBouton();

						//on met le focus sur le lien à l'intérieur du premier élément nouvellement affiché
						$('.list li:nth-of-type('+mettreFocus+') a').focus();
						
						//la nouvelle liste est prête, nouvelle animation pour réafficher
						$(resultats).animate(
							{opacity:1}, 1000, 'linear'	
						);
					}
				);
				
				
			});
			function actualiseBouton() {
				//nbre d'éléments actuellement affichés (tient compte du filtre)
				var affiche=$('.list li').length; 
				$(boutonPlus).attr('data-affiche',affiche); //on stocke cette valeur dans le bouton

				//nombre de pages automatiquement mis à jour par list.js (tient compte du filtre)
				var pages=$('.pagination li').length;
				//s'il y a plus d'une page, c'est qu'il y a encore des éléments à afficher = on montre le bouton - sinon on le cache
				if(pages > 1) {
					$(boutonPlus).show();
				} else {
					$(boutonPlus).hide();
				}
			}
		}

		/*********Afficher/masquer les filtres ressources **********/
		var toggleFiltre=$('#toggle-filtre');
		if($(toggleFiltre).length>0) {
			if(width>=768) {
				toggleFiltre.hide();
				$('#filtre-liste').show();
				$('#filtre-liste').removeAttr('aria-expanded');
			} else {
				$('#filtre-liste').hide();
				toggleFiltre.click(function(){
					if(toggleFiltre.hasClass('ouvert')) {
						$('#filtre-liste').slideUp('slow');
						$('#filtre-liste').attr('aria-expanded','false');
						$(toggleFiltre).attr('aria-expanded','false');
					} else {
						$('#filtre-liste').slideDown('slow');
						$('#filtre-liste').attr('aria-expanded','true');
						$(toggleFiltre).attr('aria-expanded','true');
					}
					toggleFiltre.toggleClass('ouvert');
				});
			}
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

		

	}); //fin document ready
})( jQuery );

