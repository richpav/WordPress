<?php
/** theme-customizer.php
 * 
 * Implementation of the Theme Customizer for Themes
 * @link		http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
 * 
 * @author		Konstantin Obenland
 * @package		The Bootstrap
 * @since		1.4.0 - 05.05.2012
 */


/**
 * Registers the theme setting controls with the Theme Customizer
 * 
 * @author	Konstantin Obenland
 * @since	1.4.0 - 05.05.2012
 * 
 * @param	WP_Customize	$wp_customize
 * 
 * @return	void
 */
function jp_podstrap_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport	= 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	
	$wp_customize->add_section( 'jp_podstrap_theme_layout', array(
		'title'		=>	__( 'Layout', 'jp-podstrap' ),
		'priority'	=>	99,
	) );
	$wp_customize->add_section( 'jp_podstrap_navbar_options', array(
			'title'		=>	__( 'Navbar Options', 'jp-podstrap' ),
			'priority'	=>	101,
	) );
	
	// Add settings
	foreach ( array_keys( jp_podstrap_get_default_theme_options() ) as $setting ) {
		$wp_customize->add_setting( "jp_podstrap_theme_options[{$setting}]", array(
			'default'		=>	jp_podstrap_options()->$setting,
			'type'			=>	'option',
			'transport'		=>	'postMessage',
		) );
	}
	
	// Theme Layout
	$wp_customize->add_control( 'jp_podstrap_theme_layout', array(
		'label'		=>	__( 'Default Layout', 'jp-podstrap' ),
		'section'	=>	'jp_podstrap_theme_layout',
		'settings'	=>	'jp_podstrap_theme_options[theme_layout]',
		'type'		=>	'radio',
		'choices'	=>	array(
			'content-sidebar'	=>	__( 'Content on left', 'jp-podstrap' ),
			'sidebar-content'	=>	__( 'Content on right', 'jp-podstrap' )
		),
	) );
	
	// Sitename in Navbar
	$wp_customize->add_control( 'jp_podstrap_navbar_site_name', array(
		'label'		=>	__( 'Add site name to navigation bar.', 'jp-podstrap' ),
		'section'	=>	'jp_podstrap_navbar_options',
		'settings'	=>	'jp_podstrap_theme_options[navbar_site_name]',
		'type'		=>	'checkbox',
	) );
	
	// Searchform in Navbar
	$wp_customize->add_control( 'jp_podstrap_navbar_searchform', array(
		'label'		=>	__( 'Add searchform to navigation bar.', 'jp-podstrap' ),
		'section'	=>	'jp_podstrap_navbar_options',
		'settings'	=>	'jp_podstrap_theme_options[navbar_searchform]',
		'type'		=>	'checkbox',
	) );
	
	// Navbar Colors
	$wp_customize->add_control( 'jp_podstrap_navbar_inverse', array(
		'label'		=>	__( 'Use inverse color on navigation bar.', 'jp-podstrap' ),
		'section'	=>	'jp_podstrap_navbar_options',
		'settings'	=>	'jp_podstrap_theme_options[navbar_inverse]',
		'type'		=>	'checkbox',
	) );
	
	// Navbar Position
	$wp_customize->add_control( 'jp_podstrap_navbar_position', array(
		'label'		=>	__( 'Navigation Bar Position', 'jp-podstrap' ),
		'section'	=>	'jp_podstrap_navbar_options',
		'settings'	=>	'jp_podstrap_theme_options[navbar_position]',
		'type'		=>	'radio',
		'choices'	=>	array(
			'static'				=>	__( 'Static.', 'jp-podstrap' ),
			'navbar-fixed-top'		=>	__( 'Fixed on top.', 'jp-podstrap' ),
			'navbar-fixed-bottom'	=>	__( 'Fixed at bottom.', 'jp-podstrap' ),
		),
	) );
}
add_action( 'customize_register', 'jp_podstrap_customize_register' );


/**
 * Adds controls to change settings instantly
 *
 * @author	Konstantin Obenland
 * @since	1.4.0 - 05.05.2012
 *
 * @return	void
 */
function jp_podstrap_customize_enqueue_scripts() {
	wp_enqueue_script( 'the-bootstrap-customize', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), _jp_podstrap_version(), true );
	wp_localize_script( 'the-bootstrap-customize', 'jp_podstrap_customize', array(
		'sitename'		=>	get_bloginfo( 'name', 'display' ),
		'searchform'	=>	jp_podstrap_navbar_searchform( false )
	) );
}
add_action( 'customize_preview_init', 'jp_podstrap_customize_enqueue_scripts' );


/* End of file theme-customizer.php */
/* Location: ./wp-content/themes/the-bootstrap/inc/theme-customizer.php */