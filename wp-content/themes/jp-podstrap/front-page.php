<?php
/** front-page.php
 *
 * Template for displaying static front page.
 *
 * Forked form /the-bootstrap/_full_width.php
 *
 * @package jp-podstrap
 * @author Josh Pollock
 * @since 0.1
 */
//define text Domain

get_header(); ?>

<section id="primary" class="col-lg-12">
	<?php tha_content_before(); ?>
	<div id="content" role="main">
		<?php tha_content_top(); ?>
		<?php
			/**TOP OF PAGE**/
			//Get the theme_options pod object
			$settings = pods('theme_options');
			//set up vars for jumbotron
			//get the tagline
			$tagline = $settings->display('front_page_tagline');
			//if it's not set use site tagline instead.
			if ( $tagline == '' ) {
				$tagline = get_bloginfo( 'description' );
			}
			//get text for jumbotron
			$front_text = $top->display('front_page_text');
			//replace with place holder text if there isn't any
			$text = jp_podstrap_or_ipsums($front_text);
			$cta = true;
			$link = 'http://google.com';
			//Do the jumbotron
			jp_podstrap_jumbotron($tagline, $text, $cta, $link);
		
			/**BOTTOM OF PAGE**/
		  	//setup parameters before getting benefits pod
			$params = array( 
				//limit to three
				'limit'   => 3
			 ); 
			//get the main benefits and put them in $benefits array
			$benefits = pods('benefit', $params);
		?>

      <!-- Example row of columns -->
      <div class="row">
      
      	<?php 
			if ( 0 < $benefits->total() ) { 
				while ( $benefits->fetch() ) { 
					//put ID in a var to be used in get_permalink
					$id = $benefits->field('ID');
					//set $short_title to the short description or some place holder text.
					$short_desc = jp_podstrap_or_ipsums(	$benefits->display('short_desc') );
					?>
					<div class="col-lg-4 fp-benefit">
						<h2><?php _e($benefits->display('short_title'), 'jp-podstrap'); ?></h2>
						<p><?php _e( $short_desc, 'jp-podstrap'); ?> </p>
						<p><a class="btn btn-primary btn-sm" href="<?php echo esc_url(get_permalink( $id) ); ?>">View details &raquo;</a></p>
					</div>
				<?php 
				}
			}
		?>
        
      </div>
		<?php
			/**Get fields for video**/
			//get the video field as array $video
			$video = $settings->field('front_page_video');
			//get video ID and mime type from that array
			$video_mime = $video['post_mime_type'];
			$video_id = $video['ID'];
			//get video source using its ID
			$video_src = wp_get_attachment_url( $video_id);
			//get title and description for video
			$video_title = $settings->field( 'video_title');
			$video_desc = $settings->field( 'video_desc' );
            		//find out if we are using a linked video and what its ID is
        		$video_use_link = $settings->field( 'use_linked_video');
            		$video_link = $settings->field( 'front_page_video_link' );
		?>
		<?php
			//show uploaded video of page if:
            		//there is video to show, and we are not using linked video
			if ( $video_id != '' && $video_use_link != 1 ) :
        	?>
				<div id="front-page-video">
					<h3 class="video-title"><?php _e( $video_title, 'jp-podstrap' ); ?></h3>
					<video controls>
						<source src="<?php echo $video_src?>" type="<?php echo $video_mime; ?>">
						Sorry your browser does not support HTML5 video.
					</video>
					<p class="video-desc"><?php _e( $video_desc, 'jp-podstrap' ); ?></p>			
				</div>
		<?php
            		endif; //endif using uploaded video
        		 //use linked video if:
            		//option is set to and a link is set
            		if ( $video_link != '' && $video_use_link == 1  ) :
        	?>
            <div id="front-page-video">
                <h3 class="video-title"><?php _e( $video_title, 'jp-podstrap' ); ?></h3>
                    <iframe src="//<?php echo $video_link; ?>" frameborder="0" allowfullscreen></iframe>
                <p class="video-desc"><?php _e( $video_desc, 'jp-podstrap' ); ?></p>
            </div>
        <?php
            endif; //using linked video
        ?>

	<?php 
		//show post content if there is any
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				//echo post content if we have any
				echo jp_podstrap_can_has_content();
			}
			jp_podstrap_content_nav( 'nav-below' );
		}
	tha_content_bottom(); ?>
	</div><!-- #content -->
	<?php tha_content_after(); ?>
</section><!-- #primary -->

<?php
get_footer();


/* End of file front-page.php */
/* Location: ./wp-content/themes/the-bootstrap/front-page.php */
