<?php
/** footer.php
 *
 * @author		Konstantin Obenland
 * @package		The Bootstrap
 * @since		1.0.0	- 05.02.2012
 */

				tha_footer_before(); ?>
				<footer id="colophon" role="contentinfo" class="col-lg-12">
					<?php tha_footer_top(); ?>
					<div id="page-footer" class="clearfix">
						<?php wp_nav_menu( array(
							'theme_location'	=>	'footer-menu',
							'menu'				=>	'footer-menu',
							'depth'     		=> 	2,
							'container'  		=> 	false,
							'menu_class' 		=> 	'nav navbar-nav',
							'fallback_cb' 		=> 	false,
							'walker'			=> 	new wp_bootstrap_navwalker()
						) );
						?>
						<div id="site-generator"<?php echo has_nav_menu('footer-menu') ? ' class="footer-nav-menu"' : ''; ?>>
							<a	href="<?php echo esc_url( __( 'http://wordpress.org/', 'jp-podstrap' ) ); ?>"
								title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'jp-podstrap' ); ?>"
								target="_blank"
								rel="generator"><?php printf( _x( 'Proudly powered by %s', 'WordPress', 'jp-podstrap' ), 'WordPress' ); ?></a>
						</div>
					</div><!-- #page-footer .well .clearfix -->
					<?php tha_footer_bottom(); ?>
				</footer><!-- #colophon -->
				<?php tha_footer_after(); ?>
			</div><!-- #page -->
		</div><!-- .container -->
	<!-- <?php printf( __( '%d queries. %s seconds.', 'jp-podstrap' ), get_num_queries(), timer_stop(0, 3) ); ?> -->
	<?php wp_footer(); ?>
	</body>
</html>
<?php


/* End of file footer.php */
/* Location: ./wp-content/themes/the-bootstrap/footer.php */