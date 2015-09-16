<?php
/*
Plugin Name: Amazing Hover Effects 
Plugin URI: http://demo.wpeffects.com/amazing-hover-effects/
Description: Amazing Hover Effects is an impressive hover effects collection, powered by pure CSS3 and iHover, no dependency. 55+ effects to choose and 3 styles.
Author: Noor-E-Alam
Author URI: http://demo.wpeffects.com/amazing-hover-effects/
Version: 4.8.1
*/

//Loading CSS
function amazing_hover_effects_css() {

	wp_enqueue_style('amazing_hover_effects_css', plugins_url( '/css/ihover.css' , __FILE__ ) );

}

add_action( 'wp_enqueue_scripts', 'amazing_hover_effects_css' );

if(!class_exists('VP_HAutoLoader')){
// Setup Contants 
defined( 'VP_PLUGIN_VERSION' ) or define( 'VP_PLUGIN_VERSION', '2.0' );
defined( 'VP_PLUGIN_URL' )     or define( 'VP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
defined( 'VP_PLUGIN_DIR' )     or define( 'VP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'VP_PLUGIN_FILE' )    or define( 'VP_PLUGIN_FILE', __FILE__ );


// Load Framework Bootstrap
require 'framework/bootstrap.php';

}

// Registering Custom Post type
add_action( 'init', 'amazing_hover_effects_custom_post' );
function amazing_hover_effects_custom_post() {
	register_post_type( 'hover-effect',
		array(
			'labels' => array(
				'name' => __( 'Hover Effects' ),
				'singular_name' => __( 'Hover Effect' ),
				'add_new_item' => __( 'Add New Hover Effect' )
			),
			'public' => true,
			'supports' => array('title'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'hover-effects'),
			'menu_icon' => '',
		)
	);
	
}

// Registering Custom post's category
add_action( 'init', 'amazing_hover_effects_custom_post_taxonomy'); 
function amazing_hover_effects_custom_post_taxonomy() {
	register_taxonomy(
		'hover_cat',  
		'hover-effect',
		array(
			'hierarchical'          => true,
			'label'                         => 'Hover Effects Category',
			'query_var'             => true,
			'show_admin_column'             => true,
			'rewrite'                       => array(
				'slug'                  => 'he-category',
				'with_front'    => true
				)
			)
	);
}
  
 

require 'admin/metabox/icon.php';

// Load Metaboxes 
new VP_Metabox(array
(
			'id'          => 'infosmeta',
			'types'       => array('hover-effect'),
			'title'       => __('Hover Image, Title, Description ', 'vp_textdomain'),
			'priority'    => 'high',
			'template' => VP_PLUGIN_DIR . '/admin/metabox/main.php'
));

require_once(VP_PLUGIN_DIR . 'admin/shortcodes.php');





//Shortcode Generator 
    function hover_effects_init_shortcodegenerator()
    {
    // Built path to shortcode generator template array file
    //$sg_path = VP_TEAM_DIR() . '/admin/shortcode.php';
    // Initialize the ShortcodeGenerator's object
    $tmpl_sg = array(
    'name' => 'sg_1',
    'template' => VP_PLUGIN_DIR . '/admin/shortcode-functions.php',
    'modal_title' => __( 'Hover Shortcode', 'vp_textdomain'),
    'button_title' => __( 'Hover Shortcode', 'vp_textdomain'),
    'types' => array( 'post', 'page' ),
    'main_image' => VP_PLUGIN_URL . '/img/hover.png',
    'sprite_image' => VP_PLUGIN_URL . '/img/hover.png',
    //'included_pages' => array( 'appearance_page_vpt_option' ),
    );
    
	$sg = new VP_ShortcodeGenerator($tmpl_sg);
    
}
	
  
  // the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
    add_action( 'after_setup_theme', 'hover_effects_init_shortcodegenerator' )

?>