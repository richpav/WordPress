<?php
/** archive-feature.php
 *
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages for "Feature" CPT
 * archive-feature.php and archive sub_feature.php are intentionally identical and should produce identical output.
 *
 * Forked from the-bootstrap/archive.php
 *
 * @package jp-podstrap
 * @author Josh Pollock
 * @since 0.1
 * @since 0.1
 */

get_header(); ?>

<section id="primary" class="col-lg-12">

	<?php tha_content_before(); ?>
	<div id="content" role="main">
		<?php tha_content_top();
		
		if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						_e( 'All Features', 'jp-podstrap' );
					endif; ?>
				</h1>
			</header><!-- .page-header -->

			<?php
			jp_podstrap_feature_archive_loop();
		tha_content_bottom(); ?>
	</div><!-- #content -->
	<?php tha_content_after(); ?>
</section><!-- #primary -->

<?php
get_footer();


/* End of file archive-feature.php */
/* Location: ./wp-content/themes/the-bootstrap/archive-feature.php */