<?php
/*
Plugin Name: WordPress SEO For Image
Plugin URI: http://wpblogtuts.wordpress.com/?p=18
Description: WordPress SEO For Image allows you to add alt and title attributes to all of your blog images.
Version: 1.1.2
Author: wpblogtuts
Author URI: http://wpblogtuts.wordpress.com/
*/
	$wp_image_seo_version="1.1.2";
	$wp_image_seo_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
	function wp_image_seo_add_pages() {
		add_options_page('WP Image SEO options', 'WP Image SEO', 'manage_options', __FILE__, 'wp_image_seo_options_page');
	}
	// Options Page
		function wp_image_seo_options_page() {
			global $wp_image_seo_version;
			// If form was submitted
			if (isset($_POST['submitted'])) {
				$alt_text=(!isset($_POST['alttext'])? '': $_POST['alttext']);
				$title_text=(!isset($_POST['titletext'])? '': $_POST['titletext']);
				$override=(!isset($_POST['override'])? 'off': 'on');
				$override_title=(!isset($_POST['override_title'])? 'off': 'on');
				update_option('wp_image_seo_alt', $alt_text);
				update_option('wp_image_seo_title', $title_text );
				update_option('wp_image_seo_override', $override );
				update_option('wp_image_seo_override_title', $override_title );
		
				$msg_status = 'WP Image SEO options saved.';
		
				// Show message
				_e('<div id="message" class="updated fade"><p>' . $msg_status . '</p></div>');
			}
			// Fetch code from DB
			$alt_text = get_option('wp_image_seo_alt');
			$title_text = get_option('wp_image_seo_title');
			$override =(get_option('wp_image_seo_override')=='on') ? "checked":"";
			$override_title =(get_option('wp_image_seo_override_title')=='on') ? "checked":"";
		
			global $wp_image_seo_plugin_url;
			$imgpath=$wp_image_seo_plugin_url.'/i';
			$action_url=$_SERVER['REQUEST_URI'];
	
			// Configuration Page
			echo <<<END
				<div class="wrap">
					<h2>WP Image SEO $wp_image_seo_version</h2>
				 	<div id="mainblock" style="width:710px">
						<form name="wpimageseoform" action="$action_url" method="post">
							<div class="dbx-content">
								<input type="hidden" name="submitted" value="1" />
								<h2>General Settings</h2>
								<p>WP Image SEO automatically adds alt and title attributes to all of your blog post images specified by parameters below.</p>
								<p>You can enter any text in a field including the following tags:</p>
								<ul>
									<li>%title - replaces post title</li>
									<li>%name - replaces image file name (without extension)</li>
									<!-- <li>%category - replaces post category</li> -->
									<!-- <li>%tags - replaces post tags</li> -->
								</ul>
								<h4>Images options</h4>
								<div>
									<label for="alt_text"><b>ALT</b> attribute (example: %name %title)</label><br>
									<input style="border:1px solid #D1D1D1;width:165px;"  id="alt_text" name="alttext" value="$alt_text"/>
								</div>
								<br>
								<div>
									<label for="title_text"><b>TITLE</b> attribute (example: %name image)</label><br>
									<input style="border:1px solid #D1D1D1;width:165px;"  id="title_text" name="titletext" value="$title_text"/>
								</div>
								<br/>
								<div>
									<input id="check1" type="checkbox" name="override" $override />
									<label for="check1">Override default WordPress image alt tag (recommended)</label>
								</div>
								<br/>
								<div>
									<input id="check2" type="checkbox" name="override_title" $override_title />
									<label for="check2">Override default WordPress image title</label>
								</div>
								<br/><br/>
								<p>
									Example:<br/>
									If you have an image named "McLaren.jpg" in a post titled "Car Image": <br/><br/>
									-Setting alt attribute to "%name %title" will produce alt="McLaren Car Image"<br/>
									-Setting title attribute to "%name image" will produce title="McLaren image"
								</p>
								<p>For detailed documentation please visit the <a href="http://wpblogtuts.wordpress.com/?p=18" target="_blank">WordPress SEO For Image</a> plugin page </p>
								<div class="submit"><input type="submit" name="Submit" value="Update options" /></div>
							</div>
						</form>
						<br/><br/><h3>&nbsp;</h3>
					</div>
				</div>
END;
		}
	
	// Add Options Page
		add_action('admin_menu', 'wp_image_seo_add_pages');
	
	function remove_extension($name) {
		return preg_replace('/(.+)\..*$/', '$1', $name);
	} 
	function wp_image_seo_process_images($matches) {
		global $post;
		$title = $post->post_title;
		$alttext_rep = get_option('wp_image_seo_alt');
		$titletext_rep = get_option('wp_image_seo_title');
		$override= get_option('wp_image_seo_override');
		$override_title= get_option('wp_image_seo_override_title');
	
		# take care of unsusal endings
		$matches[0]=preg_replace('|([\'"])[/ ]*$|', '\1 /', $matches[0]);
	
		### Normalize spacing around attributes.
		$matches[0] = preg_replace('/\s*=\s*/', '=', substr($matches[0],0,strlen($matches[0])-2));
		### Get source.
	
		preg_match('/src\s*=\s*([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/', $matches[0], $source);
	
		$saved=$source[2];
	
		### Swap with file's base name.
		preg_match('%[^/]+(?=\.[a-z]{3}\z)%', $source[2], $source);
		### Separate URL by attributes.
		$pieces = preg_split('/(\w+=)/', $matches[0], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		### Add missing pieces.
	
		$postcats=get_the_category();
		$cats="";
		if ($postcats) {
			foreach($postcats as $cat) {
				$cats = $cat->slug. ' '. $cats;
			}
		}
	
		$posttags = get_the_tags();
	
		$tags="";
		if ($posttags) {
			foreach($posttags as $tag) {
				$tags = $tag->name . ' ' . $tags;
			}
		}
	
		if (!in_array('title=', $pieces) || $override_title=="on") {
			$titletext_rep=str_replace("%title", $post->post_title, $titletext_rep);
			$titletext_rep=str_replace("%name", $source[0], $titletext_rep);
			$titletext_rep=str_replace("%category", $cats, $titletext_rep);
			$titletext_rep=str_replace("%tags", $tags, $titletext_rep);
		
			$titletext_rep=str_replace('"', '', $titletext_rep);
			$titletext_rep=str_replace("'", "", $titletext_rep);
		
			$titletext_rep=str_replace("_", " ", $titletext_rep);
			$titletext_rep=str_replace("-", " ", $titletext_rep);
			//$titletext_rep=ucwords(strtolower($titletext_rep));
			if (!in_array('title=', $pieces)) {
				array_push($pieces, ' title="' . $titletext_rep . '"');
			} else {
				$key=array_search('title=',$pieces);
				$pieces[$key+1]='"'.$titletext_rep.'" ';
			}
		}
	
		if (!in_array('alt=', $pieces) || $override=="on" ) {
			$alttext_rep=str_replace("%title", $post->post_title, $alttext_rep);
			$alttext_rep=str_replace("%name", $source[0], $alttext_rep);
			$alttext_rep=str_replace("%category", $cats, $alttext_rep);
			$alttext_rep=str_replace("%tags", $tags, $alttext_rep);
			$alttext_rep=str_replace("\"", "", $alttext_rep);
			$alttext_rep=str_replace("'", "", $alttext_rep);
			$alttext_rep=(str_replace("-", " ", $alttext_rep));
			$alttext_rep=(str_replace("_", " ", $alttext_rep));
		
			if (!in_array('alt=', $pieces)) {
				array_push($pieces, ' alt="' . $alttext_rep . '"');
			} else {
				$key=array_search('alt=',$pieces);
				$pieces[$key+1]='"'.$alttext_rep.'" ';
			}
		}
		return implode('', $pieces).' /';
	}
	function wp_image_seo_filter_content($content) {
		return preg_replace_callback('/<img[^>]+/', 'wp_image_seo_process_images', $content);
	}
	
	add_filter('the_content', 'wp_image_seo_filter_content', 100);
	
	function wp_image_seo_run_installer() {
		if(!get_option('wp_image_seo_alt')) {
			add_option('wp_image_seo_alt', '%name %title');
		}
		if(!get_option('wp_image_seo_title')) {
			add_option('wp_image_seo_title', '%title');
		}
		if(get_option('wp_image_seo_override' == '') || !get_option('wp_image_seo_override')) {
			add_option('wp_image_seo_override', 'on');
		}
		if(get_option('wp_image_seo_override_title' == '') || !get_option('wp_image_seo_override_title')) {
			add_option('wp_image_seo_override_title', 'off');
		}
	}
	add_action('plugins_loaded', 'wp_image_seo_run_installer' );
?>