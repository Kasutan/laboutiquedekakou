<?php
/**
 * ACF Customizations
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class BE_ACF_Customizations {

	public function __construct() {

		// Only allow fields to be edited on development
		if ( ! defined( 'WP_LOCAL_DEV' ) || ! WP_LOCAL_DEV ) {
			//add_filter( 'acf/settings/show_admin', '__return_false' );
		}

		// Save and sync fields.
		add_filter( 'acf/settings/save_json', array( $this, 'get_local_json_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'add_local_json_path' ) );
		add_action( 'admin_init', array( $this, 'sync_fields_with_json' ) );

		// Register options page
		add_action( 'init', array( $this, 'register_options_page' ) );

		// Register Blocks
		add_filter( 'block_categories', array($this,'kasutan_block_categories'), 10, 2 );
		add_action('acf/init', array( $this, 'register_blocks' ) );

	}

	/**
	 * Define where the local JSON is saved.
	 *
	 * @return string
	 */
	public function get_local_json_path() {
		return get_template_directory() . '/acf-json';
	}

	/**
	 * Add our path for the local JSON.
	 *
	 * @param array $paths
	 *
	 * @return array
	 */
	public function add_local_json_path( $paths ) {
		$paths[] = get_template_directory() . '/acf-json';

		return $paths;
	}

	/**
	 * Automatically sync any JSON field configuration.
	 */
	public function sync_fields_with_json() {
		if ( defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) )
			return;

		if ( ! function_exists( 'acf_get_field_groups' ) )
			return;

		$version = get_option( 'ea_acf_json_version' );

		if ( defined( 'KASUTAN_STARTER_VERSION' ) && version_compare( KASUTAN_STARTER_VERSION, $version ) ) {
			update_option( 'ea_acf_json_version', KASUTAN_STARTER_VERSION );
			$groups = acf_get_field_groups();

			if ( empty( $groups ) )
				return;

			$sync = array();
			foreach ( $groups as $group ) {
				$local    = acf_maybe_get( $group, 'local', false );
				$modified = acf_maybe_get( $group, 'modified', 0 );
				$private  = acf_maybe_get( $group, 'private', false );

				if ( $local !== 'json' || $private ) {
					// ignore DB / PHP / private field groups
					continue;
				}

				if ( ! $group['ID'] ) {
					$sync[ $group['key'] ] = $group;
				} elseif ( $modified && $modified > get_post_modified_time( 'U', true, $group['ID'], true ) ) {
					$sync[ $group['key'] ] = $group;
				}
			}

			if ( empty( $sync ) )
				return;

			foreach ( $sync as $key => $v ) {
				if ( acf_have_local_fields( $key ) ) {
					$sync[ $key ]['fields'] = acf_get_local_fields( $key );
				}
				acf_import_field_group( $sync[ $key ] );
			}
		}
	}

	/**
	 * Register Options Page
	 *
	 */
	function register_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(array(
				'page_title' 	=> 'Réglages du site La boutique de Kakou',
				'menu_title'	=> 'Réglages du site',
				'menu_slug' 	=> 'site-settings',
				'capability'	=> 'edit_posts',
				'position' 		=> '2.5',
				'icon_url' 		=> 'dashicons-store',
				'redirect'		=> false,
				'update_button' => 'Mettre à jour',
				'updated_message' => 'Réglages du site mis à jour',
				'capability' => 'manage_options',

			));
		}
	}

	/**
	* Enregistre une catégorie de blocs liée au thème
	*
	*/
	function kasutan_block_categories( $categories, $post ) {
		return array_merge(
			array(
				array(
					'slug' => 'kakou',
					'title' => 'kakou',
					'icon'  => 'cloud', //TODO
				),
			),
			$categories
		);
	}
	

	/**
	 * Register Blocks
	 * @link https://www.billerickson.net/building-gutenberg-block-acf/#register-block
	 *
	 * Categories: common, formatting, layout, widgets, embed
	 * Dashicons: https://developer.wordpress.org/resource/dashicons/
	 * ACF Settings: https://www.advancedcustomfields.com/resources/acf_register_block/
	 */
	function register_blocks() {

		if( ! function_exists('acf_register_block_type') )
			return;

		/*********Bloc tous producteurs ***************/
		acf_register_block_type( [
			'name'            => 'tous-producteurs',
			'title'           => 'Bloc tous les producteurs',
			'description'     => 'Liste de tous les producteurs, avec filtre et pagination',
			'render_template' => 'partials/blocks/tous-producteurs/tous-producteurs.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/tous-producteurs/tous-producteurs.css',
			'category'        => 'kakou',
			'icon'            => 'buddicons-replies', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>false,
				'anchor' => false,
			),
			'keywords'        => [ 'producteur', 'kakou'],
		] );
		
		/*********Bloc colonnes alternées ***************/
		acf_register_block_type( [
			'name'            => 'colonnes-alternees',
			'title'           => 'Bloc colonnes alternées',
			'description'     => 'Une colonne de texte et une colonne avec une image, alternativement à droite puis à gauche du texte - selon la position du bloc dans la page. La colonne de texte peut contenir un bouton (facultatif). Insérez ce bloc dans une page "Pleine Largeur"..',
			'render_template' => 'partials/blocks/colonnes-alternees/colonnes-alternees.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/colonnes-alternees/colonnes-alternees.css',
			'category'        => 'kakou',
			'icon'            => 'align-right', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>true,
				'anchor' => true,
			),
			'keywords'        => [ 'colonne', 'image','kakou'],
		] );

		/*********Bloc carrousel ***************/
		acf_register_block_type( [
			'name'            => 'carrousel',
			'title'           => 'Bloc carrousel',
			'description'     => 'Carrousel avec images bannières et message (cliquable en option). Insérez ce bloc dans une page "Pleine Largeur".',
			'render_template' => 'partials/blocks/carrousel/carrousel.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/carrousel/carrousel.css',
			'enqueue_script' => get_stylesheet_directory_uri() . '/partials/blocks/carrousel/carrousel.js',
			'category'        => 'kakou',
			'icon'            => 'slides', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>false,
				'anchor' => false,
			),
			'keywords'        => [ 'slide', 'carrousel','kakou','accueil'],
		] );

	}
}
new BE_ACF_Customizations();
