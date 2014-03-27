<?php
/** content-single.php
 *
 * The template for displaying content in the single.php template
 *
 * @author		Konstantin Obenland
 * @package		The Bootstrap
 * @since		1.0.0 - 07.02.2012
 */


tha_entry_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php tha_entry_top(); ?>
	
	<header class="page-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta"><?php jp_podstrap_posted_on(); ?></div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content clearfix">
		<?php
		the_content();
		jp_podstrap_link_pages(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		$categories_list = get_the_category_list( _x( ', ', 'used between list items, there is a space after the comma', 'jp-podstrap' ) );
		$tags_list = get_the_tag_list( '', _x( ', ', 'used between list items, there is a space after the comma', 'jp-podstrap' ) );
		
		if ( $categories_list )
			printf( '<span class="cat-links block">' . __( 'Posted in %1$s.', 'jp-podstrap' ) . '</span>', $categories_list );
		if ( $tags_list )
			printf( '<span class="tag-links block">' . __( 'Tagged %1$s.', 'jp-podstrap' ) . '</span>', $tags_list );
		?>
	</footer><!-- .entry-footer -->
	
	<?php tha_entry_bottom(); ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php tha_entry_after();

if ( get_the_author_meta( 'description' ) AND is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries ?>
<aside id="author-info" class="row">
	<h2 class="col-lg-8"><?php printf( __( 'About %s', 'jp-podstrap' ), get_the_author() ); ?></h2>
	<div id="author-avatar" class="col-lg-1">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'jp_podstrap_author_bio_avatar_size', 70 ) ); ?>
	</div><!-- #author-avatar -->
	<div id="author-description" class="col-lg-7">
		<?php the_author_meta( 'description' ); ?>
		<div id="author-link">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
				<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'jp-podstrap' ), get_the_author() ); ?>
			</a>
		</div><!-- #author-link	-->
	</div><!-- #author-description -->
</aside><!-- #author-info -->
<?php endif;


/* End of file content-single.php */
/* Location: ./wp-content/themes/the-bootstrap/partials/content-single.php */