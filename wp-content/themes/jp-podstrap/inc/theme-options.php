<?php
/** theme-options.php
 * 
 * The Bootstrap Theme Options
 *
 * @author		Automattic, Konstantin Obenland
 * @package		The Bootstrap
 * @since		1.3.0 - 06.04.2012
 */


/**
 * Add theme options page to the admin menu.
 *
 * @author	Automattic
 * @since	1.3.0 - 06.04.2012
 *
 * @return	void
 */
function jp_podstrap_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'jp-podstrap' ),		// Name of page
		__( 'Theme Options', 'jp-podstrap' ),		// Label in menu
		'edit_theme_options',						// Capability required
		'theme_options',							// Menu slug, used to uniquely identify the page
		'jp_podstrap_theme_options_render_page'	// Function that renders the options page
	);
	add_action( "admin_print_styles-{$theme_page}", 'jp_podstrap_admin_enqueue_scripts' );
}
add_action( 'admin_menu', 'jp_podstrap_theme_options_add_page' );


/**
 * Properly enqueue styles for theme options page.
 * 
 * @author	Automattic
 * @since	1.3.0 - 06.04.2012
 * 
 * @return	void
 */
function jp_podstrap_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'the-bootstrap-theme-options', get_template_directory_uri() . '/css/theme-options.css', false, _jp_podstrap_version() );
}


/**
 * Register the form setting for our jp_podstrap_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, jp_podstrap_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 *
 * @author	Automattic
 * @since	1.3.0 - 06.04.2012
 * 
 * @return	void
 */
function jp_podstrap_theme_options_init() {

	register_setting(
		'jp_podstrap_options',				// Options group, see settings_fields() call in jp_podstrap_theme_options_render_page()
		'jp_podstrap_theme_options',			// Database option, see jp_podstrap_options()
		'jp_podstrap_theme_options_validate'	// The sanitization callback, see jp_podstrap_theme_options_validate()
	);

	// Register settings field group
	add_settings_section(
		'general',								// Unique identifier for the settings section
		'',										// Section title (we don't want one)
		'__return_false',						// Section callback (we don't want anything)
		'theme_options'							// Menu slug, used to uniquely identify the page; see jp_podstrap_theme_options_add_page()
	);

	// Register individual settings fields
	add_settings_field( 'layout', __( 'Default Layout', 'jp-podstrap' ), 'jp_podstrap_settings_field_layout', 'theme_options', 'general' );
	add_settings_field( 'navbar', __( 'Navigation Bar', 'jp-podstrap' ), 'jp_podstrap_settings_field_checkbox', 'theme_options', 'general', array(
		(object) array(
			'name'			=>	'navbar_site_name',
			'value'			=>	jp_podstrap_options()->navbar_site_name,
			'description'	=>	__( 'Add site name to navigation bar.', 'jp-podstrap' )
		),
		(object) array(
			'name'			=>	'navbar_searchform',
			'value'			=>	jp_podstrap_options()->navbar_searchform,
			'description'	=>	__( 'Add searchform to navigation bar.', 'jp-podstrap' )
		)
	) );
	add_settings_field( 'navbar-position', __( 'Navigation Bar Position', 'jp-podstrap' ), 'jp_podstrap_settings_field_radio', 'theme_options', 'general', array(
		'name'		=>	'navbar_position',
		'options'	=>	array(
			(object) array(
				'value'			=>	'static',
				'description'	=>	__( 'Static.', 'jp-podstrap' )
			),
			(object) array(
				'value'			=>	'navbar-fixed-top',
				'description'	=>	__( 'Fixed on top.', 'jp-podstrap' )
			),
			(object) array(
				'value'			=>	'navbar-fixed-bottom',
				'description'	=>	__( 'Fixed at bottom.', 'jp-podstrap' )
			),
		)
	) );
}
add_action( 'admin_init', 'jp_podstrap_theme_options_init' );


/**
 * Change the capability required to save the 'jp_podstrap_options' options group.
 *
 * @see		jp_podstrap_theme_options_init()		First parameter to register_setting() is the name of the options group.
 * @see		jp_podstrap_theme_options_add_page()	The edit_theme_options capability is used for viewing the page.
 *
 * @author	Automattic
 * @since	1.3.0 - 06.04.2012
 * 
 * @param	string	$capability	The capability used for the page, which is manage_options by default.
 * 
 * @return	string	The capability to actually use.
 */
function jp_podstrap_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_jp_podstrap_options', 'jp_podstrap_option_page_capability' );


/**
 * Add theme options page to the admin bar.
 *
 * @author	Konstantin Obenland
 * @since	1.3.0 - 06.04.2012
 * 
 * @param	WP_Admin_Bar	$wp_admin_bar
 *
 * @return	void
 */
function jp_podstrap_admin_bar_menu( $wp_admin_bar ) {
	if ( current_user_can( 'edit_theme_options' ) AND is_admin_bar_showing() ) {
		$wp_admin_bar->add_menu( array(
			'title'		=>	__( 'Theme Options', 'jp-podstrap' ),
			'href'		=>	add_query_arg( array( 'page' => 'theme_options' ), admin_url( 'themes.php' ) ),
			'parent'	=>	'appearance',
			'id'		=>	'the-bootstrap-theme-options',
		) );
	}
}
add_action( 'admin_bar_menu', 'jp_podstrap_admin_bar_menu', 61 ); //Appearance Menu used to be added at 60


/**
 * Returns an array of layout options registered for Twenty Eleven.
 *
 * @author	WordPress.org
 * @since	1.3.0 - 06.04.2012
 * 
 * @return	void
 */
function jp_podstrap_layouts() {
	$layout_options	=	array(
		'content-sidebar'	=>	array(
			'label'		=>	__( 'Content on left', 'jp-podstrap' ),
			'thumbnail'	=>	get_template_directory_uri() . '/img/content-sidebar.png',
		),
		'sidebar-content'	=>	array(
			'label'		=>	__( 'Content on right', 'jp-podstrap' ),
			'thumbnail' =>	get_template_directory_uri() . '/img/sidebar-content.png',
		),
	);

	return apply_filters( 'jp_podstrap_layouts', $layout_options );
}


/**
 * Renders the Layout setting field.
 *
 * @author	WordPress.org
 * @since	1.3.0 - 06.04.2012
 * 
 * @return	void
 */
function jp_podstrap_settings_field_layout() {
	foreach ( jp_podstrap_layouts() as $value => $layout ) : ?>
		<label class="image-radio-option">
			<input type="radio" name="jp_podstrap_theme_options[theme_layout]" value="<?php echo esc_attr( $value ); ?>" <?php checked( jp_podstrap_options()->theme_layout, $value ); ?> />
			<span class="image-radio-label">
				<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
				<span class="description"><?php echo $layout['label']; ?></span>
			</span>
		</label>
	<?php endforeach;
}


/**
 * Renders a field with checkboxes.
 *
 * @author	WordPress.org
 * @since	1.3.0 - 06.04.2012
 *
 * @return	void
 */
function jp_podstrap_settings_field_checkbox( $options ) {
	foreach ( $options as $option ) : ?>
		<label for="<?php echo sanitize_title_with_dashes( $option->name ); ?>">
			<input type="checkbox" name="jp_podstrap_theme_options[<?php echo esc_attr( $option->name ); ?>]" id="<?php echo sanitize_title_with_dashes( $option->name ); ?>" value="1" <?php checked( $option->value ); ?> />
			<?php echo esc_html( $option->description ); ?>
		</label><br />
	<?php endforeach;
}


/**
 * Renders a field with radio buttons.
 *
 * @author	Konstantin Obenland
 * @since	1.4.0 - 12.05.2012
 *
 * @return	void
 */
function jp_podstrap_settings_field_radio( $args ) {
	extract( wp_parse_args( $args, array(
		'name'		=>	null,
		'options'	=>	array(),
	) ) );

	foreach ( (array) $options as $o ) : ?>
		<label for="<?php echo sanitize_title_with_dashes( $o->value ); ?>">
			<input type="radio" name="jp_podstrap_theme_options[<?php echo esc_attr( $name ); ?>]" id="<?php echo sanitize_title_with_dashes( $o->value ); ?>" value="<?php echo esc_attr( $o->value ); ?>" <?php checked( $o->value, jp_podstrap_options()->$name ); ?> />
			<?php if ( isset( $o->description ) ) echo $o->description; ?>
		</label><br />
	<?php endforeach;

}


/**
 * Renders the Settings page for The Bootstrap.
 *
 * @author	Automattic
 * @since	1.3.0 - 06.04.2012
 * 
 * @return	void
 */
function jp_podstrap_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php esc_html_e( 'The Bootstrap Theme Options', 'jp-podstrap' ); ?></h2>
		<?php settings_errors(); ?>

		<div id="poststuff">
			<div id="post-body" class="obenland-wp columns-2">
				<div id="post-body-content">
					<form method="post" action="options.php">
						<?php
						settings_fields( 'jp_podstrap_options' );
						do_settings_sections( 'theme_options' );
						submit_button(); ?>
					</form>
				</div>
				<div id="postbox-container-1">
					<div id="side-info-column">
						<?php do_action( 'jp_podstrap_side_info_column' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}


/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see jp_podstrap_theme_options_init()
 *
 * @author	Automattic
 * @since	1.3.0 - 06.04.2012
 * 
 * @return	void
 */
function jp_podstrap_theme_options_validate( $input ) {
	$output	= $defaults = jp_podstrap_get_default_theme_options();
	
	if ( isset( $input['theme_layout'] ) AND array_key_exists( $input['theme_layout'], jp_podstrap_layouts() ) )
		$output['theme_layout']		=	$input['theme_layout'];
	
	if ( isset( $input['navbar_position'] ) AND in_array( $input['navbar_position'], array('static', 'navbar-fixed-top', 'navbar-fixed-bottom') ) )
		$output['navbar_position']	=	$input['navbar_position'];
	
	$output['navbar_site_name']		=	isset( $input['navbar_site_name'] ) AND $input['navbar_site_name'];
	$output['navbar_searchform']	=	isset( $input['navbar_searchform'] ) AND $input['navbar_searchform'];
	
	if ( ! get_settings_errors() ) {
		add_settings_error( 'the-bootstrap-options', 'settings_updated', sprintf( __( 'Settings saved. <a href="%s">Visit your site</a> to see how it looks.', 'jp-podstrap' ), home_url( '/' ) ), 'updated' );
	}
	
	return apply_filters( 'jp_podstrap_theme_options_validate', $output, $input, $defaults );
}


///////////////////////////////////////////////////////////////////////////////
// META BOXES
///////////////////////////////////////////////////////////////////////////////

/**
 * Displays a box with a donate button and call to action links
 * 
 * Props Joost de Valk, as this is almost entirely from his awesome WordPress
 * SEO Plugin
 * 
 * @link		http://plugins.trac.wordpress.org/browser/wordpress-seo/trunk/admin/class-config.php#L82
 * @copyright	Joost de Valk
 * @license		GPLv2 or later
 *
 * @author		Joost de Valk, Konstantin Obenland
 * @since		1.3.0 - 06.04.2012
 *
 * @return		void
 */
function jp_podstrap_donate_box() {
	?>
	<div id="formatdiv" class="postbox">
		<h3 class="hndle"><span><?php esc_html_e( 'Help spread the word!', 'jp-podstrap' ); ?></span></h3>
		<div class="inside">
			<p><strong><?php printf( _x( 'Want to help make this Theme even better? All donations are used to improve %1$s, so donate $20, $50 or $100 now!', 'Plugin Name', 'jp-podstrap' ), esc_html('The Bootstrap ') ); ?></strong></p>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="542W6XT4PLT4L">
				<input type="image" src="https://www.paypalobjects.com/<?php echo get_locale(); ?>/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal">
				<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
			</form>
			<p><?php _e( 'Or you could:', 'jp-podstrap' ); ?></p>
			<ul>
				<li><a href="http://wordpress.org/extend/themes/the-bootstrap"><?php _e( 'Rate the Theme 5&#9733; on WordPress.org', 'jp-podstrap' ); ?></a></li>
				<li><a href="http://en.wp.obenland.it/the-bootstrap/"><?php _e( 'Blog about it &amp; link to the Theme page', 'jp-podstrap' ); ?></a></li>
			</ul>
		</div>
	</div>
	<?php
}
add_action( 'jp_podstrap_side_info_column', 'jp_podstrap_donate_box', 1 );


/**
 * Displays a box with feed items and social media links
 * 
 * Props Joost de Valk, as this is almost entirely from his awesome WordPress
 * SEO Plugin
 * 
 * @link		http://plugins.trac.wordpress.org/browser/wordpress-seo/trunk/admin/yst_plugin_tools.php#L375
 * @copyright	Joost de Valk
 * @license		GPLv2 or later
 * 
 * @author		Joost de Valk, Konstantin Obenland
 * @since		1.3.0 - 06.04.2012
 *
 * @return		void
 */
function jp_podstrap_feed_box() {
	$rss_items = _jp_podstrap_fetch_feed( 'http://en.wp.obenland.it/feed/' );
	?>
	<div id="formatdiv" class="postbox">
		<h3 class="hndle"><span><?php esc_html_e( 'News from Konstantin', 'jp-podstrap' ); ?></span></h3>
		<div class="inside">
			<ul>
			<?php if ( ! $rss_items ) : ?>
			<li><?php _e( 'No news items, feed might be broken...', 'jp-podstrap' ); ?></li>
			<?php else :
			foreach ( $rss_items as $item ) :
				$url = preg_replace( '/#.*/', '#utm_source=wordpress&utm_medium=sidebannerpostbox&utm_term=rssitem&utm_campaign=the-bootstrap',  $item->get_permalink() ); ?>
			<li><a class="rsswidget" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $item->get_title() ); ?></a></li>
			<?php endforeach; endif; ?>
				<li class="twitter"><a href="http://twitter.com/obenland"><?php _e( 'Follow Konstantin on Twitter', 'jp-podstrap' ); ?></a></li>
				<li class="rss"><a href="http://en.wp.obenland.it/feed/"><?php _e( 'Subscribe via RSS', 'jp-podstrap' ); ?></a></li>
			</ul>
		</div>
	</div>
	<?php
}
add_action( 'jp_podstrap_side_info_column', 'jp_podstrap_feed_box' );


/**
 * Callback function to get feed items
 * 
 * Props Joost de Valk, as this is almost entirely from his awesome WordPress
 * SEO Plugin
 * 
 * @link		http://plugins.trac.wordpress.org/browser/wordpress-seo/trunk/admin/yst_plugin_tools.php#L353
 * @copyright	Joost de Valk
 * @license		GPLv2 or later
 * 
 * @author	Joost de Valk, Konstantin Obenland
 * @since	1.3.0 - 06.04.2012
 * @access	private
 * 
 * @param	string		$feed_url
 * 
 * @return	bool|array	Array with feed items on success
 */
function _jp_podstrap_fetch_feed( $feed_url ) {
	include_once( ABSPATH . WPINC . '/feed.php' );
	$rss = fetch_feed( $feed_url );
	
	// Bail if feed doesn't work
	if ( is_wp_error( $rss ) ) {
		return false;
	}
	
	$rss_items = $rss->get_items( 0, $rss->get_item_quantity( 5 ) );
	
	// If the feed was erroneously
	if ( ! $rss_items ) {
		$md5 = md5( $feed_url );
		delete_transient( 'feed_' . $md5 );
		delete_transient( 'feed_mod_' . $md5 );
		$rss = fetch_feed( $feed_url );
		$rss_items = $rss->get_items( 0, $rss->get_item_quantity( 5 ) );
	}
	return $rss_items;
}


/* End of file theme-options.php */
/* Location: ./wp-content/themes/the-bootstrap/inc/theme-options.php */