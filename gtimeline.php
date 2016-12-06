<?php
/**
 * Plugin Name: GTimeline
 * Plugin URI: http://ganeshveer.tk
 * Description: Gtimeline is a timeline plugin based on jquery-timelinr library
 * Version: 1.0.0
 * Author: Ganesh Veer
 * Author URI: 
 * License: GPL2
 */

// Enqueue required Scripts and Style related files
function myplugin_scripts(){
if (!is_admin()) {	wp_deregister_script('jquery'); } //deregister default wp jquery as it is not compatible to timeline js ?> 
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
<?php
	wp_register_script( 'gtimeline-script',  plugin_dir_url( __FILE__ ) . 'js/jquery.timelinr-0.9.6.js' );
    wp_enqueue_script( 'gtimeline-script' );
	wp_register_style('gtimeline-style', plugin_dir_url(__FILE__) . 'css/style.css');
	wp_enqueue_style('gtimeline-style');
	//wp_register_style('gtimeline-style_v', plugin_dir_url(__FILE__) . 'css/style_v.css');
	//wp_enqueue_style('gtimeline-style_v');
}
add_action( 'wp_enqueue_scripts', 'myplugin_scripts' );

//////////////////////////////////////////
/////CREATE PLUGIN SETTINGS PAGES/////////
//////////////////////////////////////////
function ganesh_add_admin_page(){	
	//Generate Admin page
	add_menu_page('GTimeline', 'GTimeline Settings', 'manage_options', 'gtimeline_slug', 'gtimeline_create_page', 
		'dashicons-clock', 60);
	//Ganerate Admin subpages 
	add_submenu_page('gtimeline_slug', 'Gtimeline Options', 'General', 'manage_options', 'gtimeline_slug', 'gtimeline_general_create_page');
	//add_submenu_page('gtimeline_slug', 'Gtimeline Options ', 'Theme Options', 'manage_options', 'gtimeline_slug_css', 'ganesh_theme_support_page');
}

add_action('admin_menu', 'ganesh_add_admin_page');
//template submenu function
function gtimeline_create_page(){
	require_once(plugin_dir_path(__FILE__ ).'template/gtimeline-admin.php'); //Generation of admin page for General menu
}
function  gtimeline_general_create_page(){ }

//Activate custom settings
add_action('admin_init', 'gtimeline_custom_settings');
function gtimeline_custom_settings(){
	//General page
	register_setting('gtimeline-settings-group', 'orientation');
	register_setting('gtimeline-settings-group', 'autoplay');
	register_setting('gtimeline-settings-group', 'arrowkeys');
	register_setting('gtimeline-settings-group', 'startat');
	register_setting('gtimeline-settings-group', 'tl_speed');

	add_settings_section('gtimeline-settings-options', 'Settings Option', 'gtimeline_settings_options', 'gtimeline_slug');

	add_settings_field('gtimeline-orientation', 'Select Orientation','gtimline_orientation', 'gtimeline_slug', 'gtimeline-settings-options');
	add_settings_field('gtimeline-autoplay', 'Autoplay','gtimeline_autoplay', 'gtimeline_slug', 'gtimeline-settings-options');
	add_settings_field('gtimeline-arrowkey', 'ArrowKey','gtimeline_arrowkey', 'gtimeline_slug', 'gtimeline-settings-options');
	add_settings_field('gtimeline-startat', 'Start At','gtimeline_startat', 'gtimeline_slug', 'gtimeline-settings-options');
	add_settings_field('gtimeline-tl_speed', 'Timeline Speed','gtimeline_tl_speed', 'gtimeline_slug', 'gtimeline-settings-options');
}

function gtimeline_settings_options(){
	echo 'Customize Your timeline settings';
}
function gtimline_orientation(){
$options = get_option('orientation');
	$formats = array( 'horizontal', 'vertical');
	$output  = '';
	foreach ( $formats as $format ){
		$checked = ( @$options[$format] == 1 ? 'checked' : '' );
		$output .= '<label><input type="checkbox" id="'. $format .'" name="orientation['. $format .']" value="1" '. $checked .' />'. $format .'</label><br/>';
	}
	echo $output;
}
function gtimeline_autoplay(){
	$options = get_option( 'autoplay' );
	$checked = ( @$options == 1 ? 'checked' : '' );
	echo '<label><input type="checkbox" id="autoplay" name="autoplay" value="1" '.$checked.' /> </label>';
}
function gtimeline_arrowkey(){
$options = get_option( 'arrowkeys' );
	$checked = ( @$options == 1 ? 'checked' : '' );
	echo '<label><input type="checkbox" id="arrowkeys" name="arrowkeys" value="1" '.$checked.' /></label>';
}
function gtimeline_startat(){
	$startat = esc_attr(get_option('startat'));
	echo '<input type="text" id="startat" name="startat" value="'.$startat.'" /><span class="description"> Enter the slide number to be displayed initially.</span>';
}
function gtimeline_tl_speed(){
	$tl_speed = esc_attr(get_option('tl_speed'));
	echo '<input type="text" id="tl_speed" name="tl_speed" value="'.$tl_speed.'" /><span class="description"> value: integer between 100 and 1000 (recommended)</span>';	
}

//////////////////////////////////////////////////
//////CUSTOM POST TYPE - TIMELINR/////////////////
//////////////////////////////////////////////////
add_action('init', 'load_custom_posttype');
function load_custom_posttype(){
$labels = array(
			  'name' => _x('Timeline', 'post type general name', 'wp-gtimeline'),
			  'singular_name' => _x('Timeline', 'post type singular name', 'wp-gtimeline'),
			  'add_new' => _x('Add New', 'slide', 'wp-gtimeline'),
			  'add_new_item' => __('Add New Event', 'wp-gtimeline'),
			  'edit_item' => __('Edit Event', 'wp-gtimeline'),
			  'new_item' => __('New Event', 'wp-gtimeline'),
			  'view_item' => __('View Event', 'wp-gtimeline'),
			  'search_items' => __('Search Event', 'wp-gtimeline'),
			  'not_found' =>  __('No Timeline items found.', 'wp-gtimeline'),
			  'not_found_in_trash' => __('No Timeline items found in Trash.', 'wp-gtimeline'), 
			  'parent_item_colon' => ''
			);
			$args = array(
			  'labels' => $labels,
			  'public' => true,
			  'publicly_queryable' => true,
              'menu_icon' => 'dashicons-clock',
			  'show_ui' => true, 
			  'query_var' => true, 
			  'capability_type' => 'post', 
			  'menu_position' => null,
			  'rewrite' => array('slug'=>'timelinr','with_front'=>true),
			  'supports' => array('title','editor','thumbnail', 'excerpt')
			); 
register_post_type('timelinr',$args);
}

// Activate and deactivate custom post type along with plugin install and uninstall
function gtimeline_install()
{    // trigger our function that registers the custom post type
    load_custom_posttype();
     // clear the permalinks after the post type has been registered
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'gtimeline_install' );
function gtimeline_deactivation()
{
   // clear the permalinks to remove our post type's rules
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'gtimeline_deactivation' );

//Custom Meta Field Date - for timelinr post type
add_action("admin_menu", "timelinr_meta_box");
function timelinr_meta_box(){
    add_meta_box("timelineInfo-meta", __('Date', 'wp-gtimeline'), "timelinr_meta_options", "timelinr", "side", "low");
}      
function timelinr_meta_options(){
    global $post;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post->ID;

    $value = get_post_meta($post->ID, 'timelineDate', true); 
    echo '<label><input type="text" id="timelineDate" name="timelineDate" class="monthPicker" value="'.esc_attr($value).'"/><br/> Enter Year only</label>';
}
add_action('save_post', 'save_timelinr_date');   
function save_timelinr_date(){
    global $post;    
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){   
        return  $post->ID;
    }	
	if( ! isset( $_POST['timelineDate'] ) ) {
		return;
	}
    update_post_meta($post->ID, "timelineDate", $_POST['timelineDate'] );
}

///////////////////////////////////////////////////////
/////////CREATE SHORTCODE [gtimeline]/////////////////
//////////////////////////////////////////////////////
function timeline_shortcode($atts, $content = NULL) {
			global $post, $wpdb;
			STATIC $i = 1;

			$options = get_option('orientation');
			$formats = array( 'horizontal', 'vertical');
			
			foreach( $formats as $format ){
				$timelinr_options = ( @$options[$format] == 1 ? $format : '' );

			}
			if(empty($timelinr_options) ){
				$timelinr_options = "horizontal";
			}

            $timelinr_arrowkey = get_option('arrowkeys');
            $timelinr_autoplay = get_option('autoplay');
            $timelinr_startat = esc_attr(get_option('startat'));
            $timelinr_speed = esc_attr(get_option('tl_speed'));

			if($timelinr_autoplay == 1){$timelinr_autoplay = 'true';}else{$timelinr_autoplay = 'false';}
			if($timelinr_arrowkey == 1){$timelinr_arrowkey = 'true';}else{$timelinr_arrowkey = 'false';}

			$pairs = array(
					'orientation' => $timelinr_options,
					'startat' => $timelinr_startat,
					'arrowkeys' => $timelinr_arrowkey,
					'autoplay' => $timelinr_autoplay,
					'autoplaydirection' => 'forward',
					'speed'=> $timelinr_speed,
					'autoplaypause' => 2000,
					'order' => 'ASC',
					'containerdiv' => 'timelinr-'.$i,
					'category' => '',

			);
			$atts = shortcode_atts($pairs, $atts );
			//var_dump($atts);			
			ob_start();
			include (plugin_dir_path(__FILE__) . '/template/shortcode.php');
			$out = ob_get_contents();
			ob_end_clean();
			
			$i++;
			return $out;
}
add_shortcode( 'gtimeline', 'timeline_shortcode' );

//Admin CSS
function mu_custom_css(){
echo '<style>#startat{width:60px;}#tl_speed{width:60px;}</style>';
}
add_action('admin_head', 'mu_custom_css');
?>
