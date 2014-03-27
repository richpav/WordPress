<?php
/**
*
* Functions that Josh added to the theme
*
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
*/

/**
* Create "Jumbotron at top of pages
* 
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
* @param string $tagline Title for the section.
* @param string $text Text for section.
* @param sting 'jp-podstrap' Translation text domain
* @param boolean $cta Show CTA link on true. Default = false. Optional.
* @link string $link Link for CTA button. Required if $cta = true.
* @param string ctaText Text for CTA button. Optional.
*/
if ( ! function_exists('jp_podstrap_jumbotron') ) :
function jp_podstrap_jumbotron($tagline, $text, $cta = false, $link = '', $ctaText = "Call To Action") { ?>
	<!-- Jumbotron -->
	<div class="jumbotron">
		<div class="container">
			<h2><?php esc_attr_e($tagline, 'jp-podstrap'); ?></h2>
			<p class="lead"><?php _e($text, 'jp-podstrap'); ?></p>
			<?php if ($cta != false) {
				//esc cta link into a var
				$ctaLink = esc_url($link);
				echo '<a class="btn btn-large btn-success" href="'.$ctaLink.'">';
				_e($ctaText, 'jp-podstrap');
				echo '</a>';
			}
			?>
		</div>
    </div>
<?php }
endif; // ! jp_podstrap_jumbotron exists

/**
* Add JS and CSS for this
*
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
*/
if ( ! function_exists( 'jp_podstrap_scripts_styles') ) :
function jp_podstrap_scripts_styles() {
	wp_enqueue_script( 'backstretch', get_template_directory_uri().'/js/jquery.backstretch.min.js', array( 'jquery'), false, false );
	wp_enqueue_style( 'jp-style', get_template_directory_uri().'/css/jp.css' );
    //If the front page or a feature or sub_feature add inline style
    if ( is_front_page() || get_post_type() == 'benefit'  || get_post_type() == 'sub_feature' ) {
        if ( is_front_page() ) {
            //for front page get theme option pod
            $pod = pods('theme_options');
        }
        else {
            //get global $post object
            global $post;
            ///set params to get current post's pods array on
            $params = array(
                'where' => "t.id = $post->ID",
            );
            //Get from pod for current post type
            $pod = pods( get_post_type(), $params );
        }
        $title = $pod->field( 'top_title_color' );
        $text = $pod->field( 'top_text_color' );
        $inline = "
            .jumbotron h2 {color: {$title}; }
            .jumbotron p {color: {$text}; }
        ";
        wp_add_inline_style( 'jp-style', $inline );
    }
}
add_action('wp_enqueue_scripts', 'jp_podstrap_scripts_styles');
endif; // ! jp_podstrap_scripts_styles exists

/**
* Backstretch on jumbotron
*
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
*/
if ( ! function_exists( 'jp_podstrap_jumbostretch' ) ) :
function jp_podstrap_jumbostretch() {
	//First test if this is the front page or a feature or sub_feature so we have pods to pick from
	if ( is_front_page() || get_post_type() == 'benefit'  || get_post_type() == 'sub_feature' ) {
        //define $pod based on what post we are on if not on front_page
        if ( is_front_page() ) {
            //for front page get theme option pod
            $pod = pods('theme_options');
            //get background image
            $bg = $pod->field( 'bg_page');
        }
        else {
            //get global $post object
            global $post;
            ///set params to get current post's pods array on
            $params = array(
                'where' => "t.id = $post->ID",
            );
            //Get from pod for current post type
            $pod = pods( get_post_type(), $params );
            //get theme option pod
            $opt = pods('theme_options');
            //get background image
            $bg = $opt->field( 'bg_page' );

        }
		//for top bg- get the image field and turn it into ID then source URL
		$img = $pod->field( 'top_bg' );
		$img_id = $img['ID'];
		$img_src = wp_get_attachment_url( $img_id );
        //get img src from id for page bg
        $bg_img_id = $bg['ID'];
        $bg_img_src = wp_get_attachment_url( $bg_img_id );
		//output the script into the footer
		echo '<script>';
        //check if we have a top section bg img to output if so echo for that
        if ( $img_src != false ) {
            echo 'jQuery(".jumbotron").backstretch("' . $img_src . '");';
        }
        //check if we have a page bg img to show, if so echo for that too
        if ( $bg_img_src != false ) {
            echo 'jQuery.backstretch( "' . $bg_img_src . '");';
        }
		echo '</script>';
	}
}
add_action('wp_footer', 'jp_podstrap_jumbostretch');
endif; // ! jp_podstrap_jumbostretch exists

/**
* Related Features Box
*
* @returns posts in same feature group
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
*/
if ( ! function_exists ( 'jp_podstrap_related_features' ) ) :
function jp_podstrap_related_features() {
    //get the feature or benefit's feature categories
    $terms = get_the_terms(get_the_id(), 'feature_group');
    //test if there are any terms if so continue, if not then skip this
    if (!empty($terms)) {
        //get the slug foreach and put in $cats array to be fed to WP_Query
        $cats = array();
        foreach ($terms as $term) {
            $cats[] = $term->slug;
        }
        //get id of post in main loop so we can exclude it from the query we're about to do
        $c_id = get_the_ID();
        //query for posts in the same feature category(s)
        $args = array(
            'tax_query' => array(
                array(
                    'taxonomy' => 'feature_group',
                    'field' => 'slug',
                    'terms' => $cats,
                )
            ),
            'post__not_in' => array($c_id),
            'post_type' => array('benefit', 'sub_feature', 'post', 'pages'),
        );
        $query = new WP_Query($args);
        //Check if we have posts
        if ($query->have_posts()) {
            //wrap output in a well
            echo '<div class="well well-small">';
            echo '<div class="pull-left">';
            esc_attr_e('Related Features:&nbsp;', 'jp-podstrap');
            echo '</div>';
            while ($query->have_posts()) : $query->the_post();
                //Show the titles of queried posts as links
                the_title('<p class="feature-group pull-left"><a href="' . get_permalink() . '" title="' . sprintf(esc_attr__('Permalink to %s', 'jp-podstrap'), the_title_attribute('echo=0')) . '" rel="bookmark">', '</a>&nbsp;&nbsp;</p>');
            endwhile; //have posts
            echo "</div>"; //end the well
        } //end if have_posts
        //reset query
        wp_reset_postdata();
    } //endif we have terms
}
endif; // ! jp_podstrap_related_features exists

/**
* Test if there is any content to return and if nto use Lorem Ipsums
*
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
* @param string $want Text you want to return.
* @param bool $short Get short or long place holder text as fallback. Default = true which gives short.
*/
if ( ! function_exists( 'jp_podstrap_or_ipsums' ) ) :
function jp_podstrap_or_ipsums($want, $short = true) {
	//put some genuine Lorem Ispums into some vars
	$loremLong =  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean venenatis tempor nisl, et condimentum sem adipiscing ac. Suspendisse ut eros neque. Sed fermentum erat neque, at sagittis nibh pulvinar blandit. Nulla luctus eleifend venenatis. Nulla facilisi. Fusce tristique, sapien varius pulvinar sagittis, dui elit pharetra ante, a fringilla elit felis eu massa. Duis eget imperdiet arcu. Curabitur ac posuere mauris, eu tempus nisl. Suspendisse potenti. In elit augue, tristique sit amet lorem ut, ultrices auctor dui. Quisque sit amet quam lorem. Maecenas rhoncus congue placerat. Morbi molestie leo nibh, venenatis adipiscing enim dignissim ac. Donec a pulvinar lectus, id tincidunt massa. Phasellus at dui eget nisl posuere scelerisque.';
	$loremShort =  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean venenatis tempor nisl, et condimentum sem adipiscing ac. Suspendisse ut eros neque. Sed fermentum erat neque.';
	//prepare to return long or short lorem ipsum
	if ( $short != true ) {
		$get = $loremLong;
	}
	else {
		$get = $loremShort;
	}
	//Decide what to return based on if $want is empty or  equals ''
	if ( ! empty($want) || $want != '' ) {
		//you get what you wanted
		return $want;
	}
	else {
		//you get Lorem Ipsum!
		return $get;
	}
}
endif; // ! jp_podstrap_or_ipsums exists

/**
* Loop for feature and sub_feature archive pages
*
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
*/
if ( ! function_exists( 'jp_podstrap_feature_archive_loop' ) ) :
function jp_podstrap_feature_archive_loop() {
	//query for both features and sub_features toghether
	$args = array(
				'post_type' => array( 'benefit', 'sub_feature' ),
				'posts_per_page' => 5,
				'paged'	=> 'true'
		);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( '/partials/content', get_post_format() );
		} //endwhile
		jp_podstrap_content_nav();
		wp_reset_postdata();
	} //endif
}
endif; // ! jp_podstrap_feature_archive_loop exists

/**
* Don't return the title in loop on front-page, since it would look very bad
*
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
*/
if ( ! function_exists( 'jp_podstrap_no_title_front_loop' ) && ! is_admin() ) :
function jp_podstrap_no_title_front_loop($title, $id) {
    if ( is_front_page() ) {
        return '';
    }
    return $title;
}
add_filter('the_title', 'jp_podstrap_no_title_front_loop', 10, 2);
endif; // ! jp_podstrap_no_title_front_loop exists

/**
* Return admin notice if Pods isn't activate imploring user to install it.
*
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
*/
if ( ! function_exists( 'jp_podstrap_pods_nag' ) ) :
function jp_podstrap_pods_nag() {
	//url for plugins page
	$plugins = admin_url( 'plugins.php' );
	//Warning text content
	$text = 'You do not have <a href="http://pods.io" title="Pods: WordPress Evolved">Pods</a> installed and activated, which is required for this theme to function properly. You can install it through the <a href="'.$plugins.'">plugins installer</a> or download it from <a href="http://WordPress.org/plugins/pods" title="Pods download page on WordPress.org">WordPress.org/plugins/pods</a> and install it manually.';
    ?>
    <div class="updated">
        <p><?php _e( $text, 'jp-podstrap' ); ?></p>
    </div>
    <?php
}
//check if pods is active by testing for the existence of pods()
// if not throw the warning
if ( ! function_exists ( 'pods' ) ) {
	add_action( 'admin_notices', 'jp_podstrap_pods_nag' );
}
endif; // ! jp_podstrap_pods_nag exixts


/**
* Test if there is post content and return
* I can has content?
*
* @package jp-podstrap
* @author Josh Pollock
* @since 0.1
* @returns the post content 
*/
if ( ! function_exists( 'jp_podstrap_can_has_content' ) ) :
function jp_podstrap_can_has_content() {
	$cc = get_the_content();
	if($cc != '') {
		return $cc;
	}
}
endif; // ! jp_podstrap_can_has_content exists

/**
 * Output header scripts
 *
 * @package jp-podstrap
 * @author Josh Pollock
 * @since 0.1
 * @returns header_scripts field to wp_head if anything is set
 *
 */
if ( ! function_exists('jp_podstrap_header_scripts') ) :
function jp_podstrap_header_scripts() {
    //get theme options pod
    $pods = pods('theme_options');
    //get the header scripts
    $scripts = $pods->field('header_scripts');
    //echo if there's anything to echo
    $out = $scripts;
    if (! $out == '') {
          echo "\n" . stripslashes( $out ) . "\n";
    }
}
add_action('wp_head', 'jp_podstrap_header_scripts');
endif; // ! jp_podstrap_header_scripts exists

?>
