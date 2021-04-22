<?php
/**
 * kasutan functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kasutan
 */

define( 'KASUTAN_STARTER_VERSION', filemtime( get_template_directory() . '/style.css' ) );


// General cleanup
include_once( get_template_directory() . '/inc/wordpress-cleanup.php' );

// Theme
include_once( get_template_directory() . '/inc/tha-theme-hooks.php' );
include_once( get_template_directory() . '/inc/layouts.php' );
include_once( get_template_directory() . '/inc/helper-functions.php' );
include_once( get_template_directory() . '/inc/navigation.php' );
include_once( get_template_directory() . '/inc/loop.php' );
include_once( get_template_directory() . '/inc/template-tags.php' );
include_once( get_template_directory() . '/inc/site-header.php' );
include_once( get_template_directory() . '/inc/site-footer.php' );

// Editor
include_once( get_template_directory() . '/inc/disable-editor.php' );
include_once( get_template_directory() . '/inc/tinymce.php' );

// Functionality
include_once( get_template_directory() . '/inc/login-logo.php' );
include_once( get_template_directory() . '/inc/block-area.php' );
include_once( get_template_directory() . '/inc/social-links.php' );

// Plugin Support
include_once( get_template_directory() . '/inc/acf.php' );

if ( ! function_exists( 'kasutan_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function kasutan_setup() {
		
		// Body open hook
		add_theme_support( 'body-open' );
		
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails', array('post','page','evenement'));

		register_nav_menus( array(
			'primary' => 'Menu principal',
			'topbar-gauche' => 'Menu barre supérieure gauche',
			'topbar-droite' => 'Menu barre supérieure droite',
			'mobile' => 'Menu mobile',
			'footer-1' => 'Menu colonne 1 du pied de page',
			'footer-2' => 'Menu colonne 2 du pied de page',
			'footer-3' => 'Menu colonne 3 du pied de page',
			'footer-legal' => 'Menu liens légaux du pied de page'
		) );

		//Autoriser les shortcodes dans les widgets
		add_filter( 'widget_text', 'do_shortcode' );

		add_action( 'widgets_init', function() {
			

			register_sidebar(array(
				'name'=> 'Footer colonne 1',
				'id' => 'footer_1',
				'before_widget' => '<div id="%1$s" class="footer-1">',
				'after_widget' => '</div>',
				'before_title' => '<div class="h4">',
				'after_title' => '</div>',
			));

			register_sidebar(array(
				'name'=> 'Footer colonne 2',
				'id' => 'footer_2',
				'before_widget' => '<div id="%1$s" class="footer-2">',
				'after_widget' => '</div>',
				'before_title' => '<div class="h4">',
				'after_title' => '</div>',
			));

			register_sidebar(array(
				'name'=> 'Footer colonne 3',
				'id' => 'footer_3',
				'before_widget' => '<div id="%1$s" class="footer-3">',
				'after_widget' => '</div>',
				'before_title' => '<div class="h4">',
				'after_title' => '</div>',
			));
			
			register_sidebar(array(
				'name'=> 'Footer colonne 4',
				'id' => 'footer_4',
				'before_widget' => '<div id="%1$s" class="footer-4">',
				'after_widget' => '</div>',
				'before_title' => '<div class="h4">',
				'after_title' => '</div>',
			));
			
			register_sidebar(array(
				'name'=> 'Footer colonne 5',
				'id' => 'footer_5',
				'before_widget' => '<div id="%1$s" class="%2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="h5">',
				'after_title' => '</div>',
			));
			

		} );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );


		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 190,
			'width'       => 255,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Gutenberg

		// -- Responsive embeds
		add_theme_support( 'responsive-embeds' );

		// -- Wide Images
		add_theme_support( 'align-wide' );

		// -- Disable custom font sizes
		add_theme_support( 'disable-custom-font-sizes' );

		/**
		* Font sizes in editor
		* https://www.billerickson.net/building-a-gutenberg-website/#editor-font-sizes
		*/
		add_theme_support( 'editor-font-sizes', array(
			array(
				'name' => __( 'Petite', 'kakou' ),
				'size' => 16,
				'slug' => 'small'
			),
			array(
				'name' => __( 'Normale', 'kakou' ),
				'size' => 18,
				'slug' => 'normal'
			),
			array(
				'name' => __( 'Grande', 'kakou' ),
				'size' => 20,
				'slug' => 'big'
			),
			array(
				'name' => __( 'Très grande', 'kakou' ),
				'size' => 32,
				'slug' => 'huge'
			)
		) );

		

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'editor-styles.css' );
	}
endif;
add_action( 'after_setup_theme', 'kasutan_setup' );

/**
* Register color palette for Gutenberg editor.
*/
require get_template_directory() . '/inc/colors.php';

// -- Disable Custom Colors
add_theme_support( 'disable-custom-colors' );


/**
 * Enqueue scripts and styles.
 */
function kasutan_scripts() {
	wp_enqueue_style( 'kakou-owl-carousel', get_template_directory_uri() . '/lib/owlcarousel/owl.carousel.min.css',array(),'2.3.4');
	wp_enqueue_style( 'kakou-style', get_stylesheet_uri(),array(), filemtime( get_template_directory() . '/style.css' ) );
	wp_enqueue_style( 'kakou-google-font', 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300&display=swap');

	// Move jQuery to footer
	if( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.js' ), false, NULL, true );
		wp_enqueue_script( 'jquery' );
	}
	
	wp_enqueue_script( 'kakou-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'kakou-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'kakou-owl-carousel',get_template_directory_uri() . '/lib/owlcarousel/owl.carousel.min.js', array('jquery'), '2.3.4', true );

	wp_register_script( 'kakou-modaal',get_template_directory_uri() . '/lib/modaal/modaal.min.js', array('jquery'), '0.4.4', true );

	wp_register_script( 'kakou-list',get_template_directory_uri() . '/lib/list/list.min.js', array(), '1.5.0', true );

	wp_enqueue_script( 'kakou-scripts', get_template_directory_uri() . '/js/main.js', array('jquery', 'kakou-owl-carousel', 'kakou-modaal','kakou-list'), '', true );
}
add_action( 'wp_enqueue_scripts', 'kasutan_scripts' );


/**
* Image sizes. Work first with medium and large in admin if possible
* https://developer.wordpress.org/reference/functions/add_image_size/
*/

//add_image_size('banniere',1600,700,false);
//add_image_size('logo',160,150,false);

/**
* CPT, custom fields, custom taxonomies et functions associées
*/

require_once( 'inc/cpt-taxonomies.php' );




/**
* Reusable Blocks accessible in backend
* @link https://www.billerickson.net/reusable-blocks-accessible-in-wordpress-admin-area
*
*/
function kasutan_reusable_blocks_admin_menu() {
	add_menu_page( 'Blocs réutilisables', 'Blocs réutilisables', 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 22 );
}
add_action( 'admin_menu', 'kasutan_reusable_blocks_admin_menu' );




/**
 * Afficher tous les résultats sans pagination sur page résultats de recherche
 */
function kasutan_remove_pagination( $query ) {
	if ( $query->is_main_query() &&  get_query_var( 's', 0 ) ) {
		$query->query_vars['nopaging'] = 1;
		$query->query_vars['posts_per_page'] = -1;
	}
}
add_action( 'pre_get_posts', 'kasutan_remove_pagination' );


/**
* Template Hierarchy
*
*/
function ea_template_hierarchy( $template ) {

	if( is_home() || is_search() )
		$template = get_query_template( 'archive' );
	return $template;
}
add_filter( 'template_include', 'ea_template_hierarchy' );