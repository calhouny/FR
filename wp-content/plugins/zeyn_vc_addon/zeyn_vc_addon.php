<?php
defined('ABSPATH') or die();
/*
Plugin Name: Detheme Visual Composer Add On
Plugin URI: http://www.detheme.com/
Description: Module add on for WPBakery Visual Composer.
Version: 1.0.8
Author: detheme.com
Author URI: http://www.detheme.com/
*/

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

class DethemeVC{

    function init(){

      if(!function_exists('vc_set_as_theme'))
        return true;


        load_plugin_textdomain('detheme', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        define('DETHEME_VC_BASENAME',dirname(plugin_basename(__FILE__)));
        define('DETHEME_VC_DIR',plugin_dir_path(__FILE__));
        define('DETHEME_VC_DIR_URL',plugin_dir_url(__FILE__));

       if(version_compare(WPB_VC_VERSION,"4.2.3",'<')){
          require_once(plugin_dir_path(__FILE__)."lib/map.old.php");
        }
        else{
          require_once(plugin_dir_path(__FILE__)."lib/map.php");
        }

        require_once(plugin_dir_path(__FILE__)."lib/shortcode.php");

        add_action('wp_enqueue_scripts', array($this,'load_front_css_style' ));
        add_action('admin_enqueue_scripts', array($this,'load_editor_css_style' ));
        add_filter( 'plugin_row_meta', array($this,'vc_compatible_version'),1,4);//
        wp_enqueue_script('dt-vc-addon',DETHEME_VC_DIR_URL.'js/script.js',array('jquery'));


    }

     function vc_compatible_version($plugin_meta, $plugin_file, $plugin_data, $status){

      if('js_composer/js_composer.php'!=$plugin_file)
        return $plugin_meta;
      $plugin_meta=array();

      if ( !empty( $plugin_data['Version'] ) )
        $plugin_meta[] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
      if ( !empty( $plugin_data['Author'] ) ) {
        $plugin_meta[] = sprintf( __( 'By %s' ), $plugin_data['Author'] );
      }


      return $plugin_meta;
    }


    function load_editor_css_style(){
        wp_enqueue_style('fontello-font',DETHEME_VC_DIR_URL."css/fontello.css");
        wp_enqueue_style( 'detheme-vc-front',DETHEME_VC_DIR_URL."lib/admin/css/admin.css?".time(),array());

        if(version_compare(WPB_VC_VERSION,"4.2.3",'>=')){
           wp_enqueue_script('detheme-vc-backend',DETHEME_VC_DIR_URL.'lib/admin/js/backend.new.js',array('jquery'));
        }

        wp_enqueue_script('icon-picker',DETHEME_VC_DIR_URL.'lib/admin/js/icon_picker.js',array('jquery'));

    }

    function load_front_css_style(){

        wp_register_style('fontello-font',DETHEME_VC_DIR_URL."css/fontello.css");
        wp_register_style('detheme-vc',DETHEME_VC_DIR_URL."css/plugin_style.css",array('fontello-font'));


        wp_register_style('scroll-spy',DETHEME_VC_DIR_URL."css/scroll_spy.css",array('scroll-spy-ie'));
        wp_register_style( 'scroll-spy-ie', get_template_directory_uri() . '/css/scroll_spy_ie9.css', array());
        wp_style_add_data( 'scroll-spy-ie', 'conditional', 'IE 9' );


        wp_register_script( 'uilkit', DETHEME_VC_DIR_URL . 'js/uilkit.js', array(), '1.0', false );
        wp_register_script('ScrollSpy',DETHEME_VC_DIR_URL."js/scrollspy.js",array( 'uilkit' ), '1.0', false );

        wp_enqueue_style( 'detheme-vc');
    }

}

add_action('init', array(new DethemeVC(),'init'),1);

if(!function_exists('darken')){

    function darken($colourstr, $procent=0) {
      $colourstr = str_replace('#','',$colourstr);
      $rhex = substr($colourstr,0,2);
      $ghex = substr($colourstr,2,2);
      $bhex = substr($colourstr,4,2);

      $r = hexdec($rhex);
      $g = hexdec($ghex);
      $b = hexdec($bhex);

      $r = max(0,min(255,$r - ($r*$procent/100)));
      $g = max(0,min(255,$g - ($g*$procent/100)));  
      $b = max(0,min(255,$b - ($b*$procent/100)));

      return '#'.str_repeat("0", 2-strlen(dechex($r))).dechex($r).str_repeat("0", 2-strlen(dechex($g))).dechex($g).str_repeat("0", 2-strlen(dechex($b))).dechex($b);
    }

}

if(!function_exists('lighten')){

    function lighten($colourstr, $procent=0){

      $colourstr = str_replace('#','',$colourstr);
      $rhex = substr($colourstr,0,2);
      $ghex = substr($colourstr,2,2);
      $bhex = substr($colourstr,4,2);

      $r = hexdec($rhex);
      $g = hexdec($ghex);
      $b = hexdec($bhex);

      $r = max(0,min(255,$r + ($r*$procent/100)));
      $g = max(0,min(255,$g + ($g*$procent/100)));  
      $b = max(0,min(255,$b + ($b*$procent/100)));

      return '#'.str_repeat("0", 2-strlen(dechex($r))).dechex($r).str_repeat("0", 2-strlen(dechex($g))).dechex($g).str_repeat("0", 2-strlen(dechex($b))).dechex($b);
    }

}
?>