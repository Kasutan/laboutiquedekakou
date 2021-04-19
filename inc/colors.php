<?php
/**
 * Register Custom color palette for Gutenberg editor
 *
 * Should be the colors from css/colors.css.
 *
 * @package kasutan
 */

add_theme_support( 'editor-color-palette', array(
	array(
		'name'  =>'Bleu foncé',
		'slug'  => 'bleu-fonce',
		'color'	=> '#30358C',
	),
	array(
		'name'  =>'Bleu',
		'slug'  => 'bleu',
		'color'	=> '#0B499A',
	),
	array(
		'name'  =>'Cyan',
		'slug'  => 'cyan',
		'color'	=> '#37b0b0',
	),
	array(
		'name'  =>'Bleu clair',
		'slug'  => 'bleu-clair',
		'color'	=> '#EAF9F9',
	),
	
	array(
		'name'  =>'Rouge',
		'slug'  => 'rouge',
		'color'	=> '#D61530',
	),
	array(
		'name'  =>'Violet',
		'slug'  => 'violet',
		'color'	=> '#99066E',
	),

	
	array(
		'name'  =>'Gris',
		'slug'  => 'gris',
		'color'	=> '#82879A',
	),
	array(
		'name'  =>'Blanc',
		'slug'  => 'blanc',
		'color'	=> '#ffffff',
	),
	array(
		'name'  =>'Noir',
		'slug'  => 'noir',
		'color'	=> '#101631',
	),
));