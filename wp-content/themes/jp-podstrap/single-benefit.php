<?php
/** single-feature.php
 *
 * The Template for displaying all single posts of the "Feature" CPT.
 *
 * Forked from the-bootstrap/single.php
 *
 * @package jp-podstrap
 * @author Josh Pollock
 * @since 0.1
 */

get_header(); ?>

<section id="primary" class="col-lg-12">
	
	<?php tha_content_before(); ?>
	<div id="content" role="main">
		<?php tha_content_top();

		while ( have_posts() ) {
			the_post();
			/**SETUP PODS OBJECT**/
			//get for current benefit 
			$benefit = pods( 'benefit', get_the_id() );
			/**TOP OF PAGE**/
			//set up vars for jumbotron
			$tag = get_the_title();
			//get the short description
			$text = get_post_meta( get_the_id(), 'short_desc', true );
			//Find out if we are showing a CTA button and if so get text and link
			$show = $benefit->field( 'show_cta_button' );
			//set $cta, $link and CTA to empty values, and then set them to actual values if necessary.
			$cta = false;
			$link = '';
			$ctaText = '';
			if ( $show != 0 ) {
				//set $cta to true
				$cta = true;
				//get link and text for the button
				$link = $benefit->field( 'cta_link' );
				$ctaText = $benefit->field( 'cta_btn_text' );
			}
			//do jumbotron
			jp_podstrap_jumbotron($tag, $text, $cta = false, $link = '', $ctaText = "Call To Action" );
			

			/**SUBFEATURE SECTION**/
			//Put the sub features in an array
			$subFeatures = $benefit->field('sub_features');
            //get the id of the default icon, just in case we need it
            $settings = pods( 'theme_options' );
            $default_icon = $settings->field( 'default_feature_icon' );
            $default_icon_id = $default_icon['ID'];
			//loop through them creating links to their own pages if there is anything to loop through
			if ( ! empty( $subFeatures ) ) {
					foreach ($subFeatures as $subFeature) { 
						//get id for sub features page and put in $id
						$id = $subFeature['ID'];
						//get the short description from sub feature
						$short_desc = get_post_meta( $id, 'short_desc', true );
						//get the icon field meta
						$icon = get_post_meta( $id, 'icon', true );
						//get the ID for the icon, if $icon isn't empty
                        if ( ! empty( $icon) ) {
						    $icon_id = $icon['ID'];
                        }
                        else {
                            //if no icon is set use the id of the default icon instead
                            $icon_id = $default_icon_id;
                        }
			?>
				<div class="row  well well-small" style="margin-right:2px; margin-left: 2px">
					<div class="col-lg-2">
						<?php  echo wp_get_attachment_image( $icon_id, 'thumbnail' ); ?>
					</div>
					<div class="col-lg-10">
						<a href="<?php echo esc_url( get_permalink($id) ); ?>">
							<h4><?php _e( get_the_title($id), 'jp-podstrap' ); ?></h4>
						</a>
   						<P><?php _e( $short_desc, 'jp-podstrap' ); ?></p>
						<button type="button" class="btn btn-default pull-right">
							<a href="<?php echo esc_url( get_permalink($id) ); ?>">
								<?php _e( 'Learn More', 'jp-podstrap' ); ?>
							</a>
						</button>
					</div>
				</div>
			<?php   } //end of foreach
				} //endif
			//echo post content if we have any
			echo jp_podstrap_can_has_content();
		} //end while have_posts 
		jp_podstrap_related_features( $parent = true );
		tha_content_bottom(); ?>
	</div><!-- #content -->
	<?php tha_content_after(); ?>
</section><!-- #primary -->

<?php
get_footer();


/* End of file single-feature.php */
/* Location: ./wp-content/themes/the-bootstrap/single-feature.php */