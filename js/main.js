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

