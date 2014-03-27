<?php
/** header.php
 *
 * Displays all of the <head> section and everything up till </header>
 *
 * @author		Konstantin Obenland
 * @package		The Bootstrap
 * @since		1.0 - 05.02.2012
 *
 * @package jp-podstrap
 * @author Josh Pollock
 * @since 0.1
 */

/**
 * First test if Pods is activated.
 * If not switch to default theme.
 */
if ( ! function_exists( 'pods')) {
    switch_theme(WP_DEFAULT_THEME);
}
else {
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<?php tha_head_top(); ?>
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		<title><?php wp_title( '&laquo;', true, 'right' ); ?></title>
		
		<?php tha_head_bottom(); ?>
		<?php wp_head(); ?>
	</head>
	
	<body <?php body_class(); ?>>
		<div class="container">
			<div id="page" class="hfeed row">
				<?php tha_header_before(); ?>
				<header id="branding" role="banner" class="col-lg-12">
					<?php tha_header_top();
					wp_nav_menu( array(
						'theme_location'	=>	'header-menu',
						'menu'				=>	'header-menu',
						'depth'     		 => 2,
						'container'  		=> false,
						'menu_class' 		=> 'nav navbar-nav',
						'fallback_cb' 		=> false,
						'walker'			 => new wp_bootstrap_navwalker()
					) ); ?>
					<hgroup>
						<h1 id="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
								<span><?php bloginfo( 'name' ); ?></span>
							</a>
						</h1>
						<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
					</hgroup>
					
					<?php if ( get_header_image() ) : ?>
					<a id="header-image" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
					</a>
					<?php endif; // if ( get_header_image() ) ?>

					<nav id="access" role="navigation">
						<h3 class="assistive-text"><?php _e( 'Main menu', 'jp-podstrap' ); ?></h3>
						<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'jp-podstrap' ); ?>"><?php _e( 'Skip to primary content', 'jp-podstrap' ); ?></a></div>
						<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'jp-podstrap' ); ?>"><?php _e( 'Skip to secondary content', 'jp-podstrap' ); ?></a></div>
						<?php if ( has_nav_menu( 'primary' ) OR jp_podstrap_options()->navbar_site_name OR jp_podstrap_options()->navbar_searchform ) : ?>
						<div <?php jp_podstrap_navbar_class(); ?>>
							<div class="navbar-inner">
								<div class="container">
									<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
									<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</a>
									<?php if ( jp_podstrap_options()->navbar_site_name ) : ?>
									<span class="brand"><?php bloginfo( 'name' ); ?></span>
									<?php endif;?>
									<div class="nav-collapse">
										<?php wp_nav_menu( array(
											'theme_location'	=> 'primary',
											'menu'       		=> 'primary',
											'depth'      		=> 2,
											'container' 		=> false,
											'menu_class' 		=> 'nav navbar-nav',
											'fallback_cb' 		=> 'wp_bootstrap_navwalker::fallback',
											'walker' => new wp_bootstrap_navwalker()
										) ); 
										if ( jp_podstrap_options()->navbar_searchform ) {
											jp_podstrap_navbar_searchform();
										} ?>
								    </div>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</nav><!-- #access -->
					<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<nav id="breadcrumb" class="breadcrumb">', '</nav>' );
					}
					tha_header_bottom(); ?>
				</header><!-- #branding --><?php
				tha_header_after();
}

/* End of file header.php */
/* Location: ./wp-content/themes/the-bootstrap/header.php */