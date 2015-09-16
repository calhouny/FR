<?php
/*
Plugin Name: Hero Boxes
Description: An addon for Visual Composer for showcasing your images in hero boxes.
Author: Gambit Technologies
Version: 1.1
Author URI: http://gambit.ph
Plugin URI: http://codecanyon.net/user/gambittech/portfolio
Text Domain: gambit-hero-box
Domain Path: /languages
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Identifies the plugin itself. If already existing, it will not redefine itself.
defined( 'VERSION_GAMBIT_HERO_BOX' ) or define( 'VERSION_GAMBIT_HERO_BOX', '1.1' );

// Initializes the plugin translations.
defined( 'GAMBIT_HERO_BOX' ) or define( 'GAMBIT_HERO_BOX', 'gambit-hero-box' );

if ( ! class_exists('GambitHeroBox') ) {
	
class GambitHeroBox {

	// Used for loading stuff only once during a page load
    private static $firstLoad = 0;

    public $type = array();
    
	/**
	 * Hook into WordPress
	 *
	 * @return	void
	 * @since	1.0
	 */
	function __construct() {
        $this->type = array(
            __( 'Random', GAMBIT_HERO_BOX ) => 'random',
            __( 'Lily', GAMBIT_HERO_BOX ) => 'lily',
            __( 'Sadie', GAMBIT_HERO_BOX ) => 'sadie',
            __( 'Honey', GAMBIT_HERO_BOX ) => 'honey',
            __( 'Layla', GAMBIT_HERO_BOX ) => 'layla',
            __( 'Zoe', GAMBIT_HERO_BOX ) => 'zoe',
            __( 'Oscar', GAMBIT_HERO_BOX ) => 'oscar',
            __( 'Marley', GAMBIT_HERO_BOX ) => 'marley',
            __( 'Ruby', GAMBIT_HERO_BOX ) => 'ruby',
            __( 'Roxy', GAMBIT_HERO_BOX ) => 'roxy',
            __( 'Bubba', GAMBIT_HERO_BOX ) => 'bubba',
            __( 'Romeo', GAMBIT_HERO_BOX ) => 'romeo',
            __( 'Dexter', GAMBIT_HERO_BOX ) => 'dexter',
            __( 'Sarah', GAMBIT_HERO_BOX ) => 'sarah',
            __( 'Chico', GAMBIT_HERO_BOX ) => 'chico',
            __( 'Milo', GAMBIT_HERO_BOX ) => 'milo',
            __( 'Julia', GAMBIT_HERO_BOX ) => 'julia',
            __( 'Goliath', GAMBIT_HERO_BOX ) => 'goliath',
            __( 'Hera', GAMBIT_HERO_BOX ) => 'hera',
            __( 'Winston', GAMBIT_HERO_BOX ) => 'winston',
            __( 'Selena', GAMBIT_HERO_BOX ) => 'selena',
            __( 'Terry', GAMBIT_HERO_BOX ) => 'terry',
            __( 'Apollo', GAMBIT_HERO_BOX ) => 'apollo',
            __( 'Kira', GAMBIT_HERO_BOX ) => 'kira',
            __( 'Steve', GAMBIT_HERO_BOX ) => 'steve',
            __( 'Moses', GAMBIT_HERO_BOX ) => 'moses',
            __( 'Jazz', GAMBIT_HERO_BOX ) => 'jazz',
            __( 'Ming', GAMBIT_HERO_BOX ) => 'ming',
            __( 'Lexi', GAMBIT_HERO_BOX ) => 'lexi',
            __( 'Duke', GAMBIT_HERO_BOX ) => 'duke'
        );

		add_action( 'after_setup_theme', array( $this, 'initVisualComposer' ), 1 );

		add_shortcode( 'hero_box', array( $this, 'renderShortcode' ) );
        
        add_shortcode( 'hero_box_gallery', array( $this, 'renderShortcodeGallery' ) );
        
		// Our translations
		add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ), 1 );

		// Gambit links
		add_filter( 'plugin_row_meta', array( $this, 'pluginLinks' ), 10, 2 );
        
        add_filter( 'attachment_fields_to_edit', array( $this, 'addTileLinkFields' ), 10, 2 );
        
        add_filter( 'attachment_fields_to_save', array( $this, 'saveTileLinkFields' ), 10 , 2 );
        
		// Activation instructions & CodeCanyon rating notices
		$this->createNotices();
	}


	/**
	 * Loads the translations
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function loadTextDomain() {
		load_plugin_textdomain( GAMBIT_HERO_BOX, false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * Initializes our VC integration
	 *
	 * @return	void
	 * @since	1.0
	 */
    public function initVisualComposer() {
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            return;
        }
        if ( version_compare( WPB_VC_VERSION, '4.2', '<' ) ) {
    		add_action( 'after_setup_theme', array( $this, 'createShortcode' ) );
    		add_action( 'after_setup_theme', array( $this, 'createShortcodeGallery' ) );
        } else {
    		add_action( 'vc_after_mapping', array( $this, 'createShortcode' ) );
    		add_action( 'vc_after_mapping', array( $this, 'createShortcodeGallery' ) ); 
        }
    }


	/**
	 * Adds plugin links
	 *
	 * @access	public
	 * @param	array $plugin_meta The current array of links
	 * @param	string $plugin_file The plugin file
	 * @return	array The current array of links together with our additions
	 * @since	1.0
	 **/
	public function pluginLinks( $plugin_meta, $plugin_file ) {
		if ( $plugin_file == plugin_basename( __FILE__ ) ) {
			$pluginData = get_plugin_data( __FILE__ );

			$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
				"http://support.gambit.ph?utm_source=" . urlencode( $pluginData['Name'] ) . "&utm_medium=plugin_link",
				__( "Get Customer Support", GAMBIT_HERO_BOX )
			);
			$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
				"https://gambit.ph/plugins?utm_source=" . urlencode( $pluginData['Name'] ) . "&utm_medium=plugin_link",
				__( "Get More Plugins", GAMBIT_HERO_BOX )
			);
		}
		return $plugin_meta;
	}



	/************************************************************************
	 * Activation instructions & CodeCanyon rating notices START
	 ************************************************************************/
	/**
	 * For theme developers who want to include our plugin, they will need
	 * to disable this section. This can be done by include this line
	 * in their theme:
	 *
	 * defined( 'GAMBIT_DISABLE_RATING_NOTICE' ) or define( 'GAMBIT_DISABLE_RATING_NOTICE', true );
	 */

	/**
	 * Adds the hooks for the notices
	 *
	 * @access	protected
	 * @return	void
	 * @since	1.0
	 **/
	protected function createNotices() {
		register_activation_hook( __FILE__, array( $this, 'justActivated' ) );
		register_deactivation_hook( __FILE__, array( $this, 'justDeactivated' ) );

		if ( defined( 'GAMBIT_DISABLE_RATING_NOTICE' ) ) {
			return;
		}

		add_action( 'admin_notices', array( $this, 'remindSettingsAndSupport' ) );
		add_action( 'admin_notices', array( $this, 'remindRating' ) );
		add_action( 'wp_ajax_' . __CLASS__ . '-ask-rate', array( $this, 'ajaxRemindHandler' ) );
	}


	/**
	 * Creates the transients for triggering the notices when the plugin is activated
	 *
	 * @return	void
	 * @since	1.0
	 **/
	public function justActivated() {
		delete_transient( __CLASS__ . '-activated' );
		
		set_transient( __CLASS__ . '-activated', time(), MINUTE_IN_SECONDS * 2 );
		
		if ( get_option( __CLASS__ . '-prev-activated' ) !== false ) {
			return;
		}
		update_option( __CLASS__ . '-prev-activated', 1 );

		if ( defined( 'GAMBIT_DISABLE_RATING_NOTICE' ) ) {
			return;
		}

		delete_transient( __CLASS__ . '-ask-rate' );
		set_transient( __CLASS__ . '-ask-rate', time(), DAY_IN_SECONDS * 4 );

		update_option( __CLASS__ . '-ask-rate-placeholder', 1 );
	}
	
    /**
    *   
    **/
    public function addTileLinkFields( $form_fields, $post ) {
        $form_fields['hero-box-tile-link-to'] = array(
            'label' => __( 'Tile Link To', GAMBIT_HERO_BOX ),
            'input' => 'text',
            'value' => get_post_meta( $post->ID, '_hero-box-tile-link-to', true ),
            'helps' => __( 'The URL to go to when the image tile is clicked. This is only for the Hero Box for Visual Composer addon.', GAMBIT_HERO_BOX ),
        );

        $value = get_post_meta( $post->ID, '_hero-box-tile-link-to-new-window', true );
        $checked = checked( $value, '1', false );
        $form_fields['hero-box-tile-link-to-new-window'] = array(
            'label' => '',
            'input' => 'html',
            'value' => (bool) $value,
            'html' => "<label><input type='checkbox' name='attachments[{$post->ID}][hero-box-tile-link-to-new-window]' value='1' {$checked}/> " . __( 'Open link in new window', GAMBIT_HERO_BOX ) . "</label>",
        );
        return $form_fields;
    }
    
    public function saveTileLinkFields( $post, $attachment ) {
        if( isset( $attachment['hero-box-tile-link-to'] ) ) {
            update_post_meta( $post['ID'], '_hero-box-tile-link-to', $attachment['hero-box-tile-link-to'] );
        }

        $newWindow = isset( $attachment['hero-box-tile-link-to-new-window'] ) ? '1' : '0';
        update_post_meta( $post['ID'], '_hero-box-tile-link-to-new-window', $newWindow );

        return $post;
    }

	/**
	 * Removes the transients & triggers when the plugin is deactivated
	 *
	 * @return	void
	 * @since	1.0
	 **/
	public function justDeactivated() {
		delete_transient( __CLASS__ . '-activated' );
		delete_transient( __CLASS__ . '-ask-rate' );
		delete_option( __CLASS__ . '-ask-rate-placeholder' );
	}


	/**
	 * Ajax handler for when a button is clicked in the 'ask rating' notice
	 *
	 * @return	void
	 * @since	1.0
	 **/
	public function ajaxRemindHandler() {
		check_ajax_referer( __CLASS__, '_nonce' );

		if ( $_POST['type'] == 'remove' ) {
			delete_option( __CLASS__ . '-ask-rate-placeholder' );
		} else { // remind
			set_transient( __CLASS__ . '-ask-rate', time(), DAY_IN_SECONDS );
		}

		die();
	}


	/**
	 * Displays the notice for reminding the user to rate our plugin
	 *
	 * @return	void
	 * @since	1.0
	 **/
	public function remindRating() {
		if ( defined( 'GAMBIT_DISABLE_RATING_NOTICE' ) ) {
			return;
		}
		if ( get_option( __CLASS__ . '-ask-rate-placeholder' ) === false ) {
			return;
		}
		if ( get_transient( __CLASS__ . '-ask-rate' ) ) {
			return;
		}

		$pluginData = get_plugin_data( __FILE__ );
		$nonce = wp_create_nonce( __CLASS__ );

		echo '<div class="updated gambit-ask-rating" style="border-left-color: #3498db">
				<p>
					<img src="' . plugins_url( 'gambit-logo.png', __FILE__ ) . '" style="display: block; margin-bottom: 10px"/>
					<strong>' . sprintf( __( 'Enjoying %s?', GAMBIT_HERO_BOX ), $pluginData['Name'] ) . '</strong><br>' .
					__( 'Help us out by rating our plugin 5 stars in CodeCanyon! This will allow us to create more awesome products and provide top notch customer support.', GAMBIT_HERO_BOX ) . '<br>' .
					'<button data-href="http://codecanyon.net/downloads?utm_source=' . urlencode( $pluginData['Name'] ) . '&utm_medium=rate_notice" class="button button-primary" style="margin: 10px 10px 10px 0;">' . __( 'Rate us 5 stars in CodeCanyon :)', GAMBIT_HERO_BOX ) . '</button>' .
					'<button class="button button-secondary remind" style="margin: 10px 10px 10px 0;">' . __( 'Remind me tomorrow', GAMBIT_HERO_BOX ) . '</button>' .
					'<button class="button button-secondary nothanks" style="margin: 10px 0;">' . __( 'I&apos;ve already rated!', GAMBIT_HERO_BOX ) . '</button>' .
					'<script>
					jQuery(document).ready(function($) {
						"use strict";

						$(".gambit-ask-rating button").click(function() {
							if ( $(this).is(".button-primary") ) {
								var $this = $(this);

								var data = {
									"_nonce": "' . $nonce . '",
									"action": "' . __CLASS__ . '-ask-rate",
									"type": "remove"
								};

								$.post(ajaxurl, data, function(response) {
									$this.parents(".updated:eq(0)").fadeOut();
									window.open($this.attr("data-href"), "_blank");
								});

							} else if ( $(this).is(".remind") ) {
								var $this = $(this);

								var data = {
									"_nonce": "' . $nonce . '",
									"action": "' . __CLASS__ . '-ask-rate",
									"type": "remind"
								};

								$.post(ajaxurl, data, function(response) {
									$this.parents(".updated:eq(0)").fadeOut();
								});

							} else if ( $(this).is(".nothanks") ) {
								var $this = $(this);

								var data = {
									"_nonce": "' . $nonce . '",
									"action": "' . __CLASS__ . '-ask-rate",
									"type": "remove"
								};

								$.post(ajaxurl, data, function(response) {
									$this.parents(".updated:eq(0)").fadeOut();
								});
							}
							return false;
						});
					});
					</script>
				</p>
			</div>';
	}


	/**
	 * Displays the notice that we have a support site and additional instructions
	 *
	 * @return	void
	 * @since	1.0
	 **/
	public function remindSettingsAndSupport() {
		if ( defined( 'GAMBIT_DISABLE_RATING_NOTICE' ) ) {
			return;
		}
		if ( ! get_transient( __CLASS__ . '-activated' ) ) {
			return;
		}

		$pluginData = get_plugin_data( __FILE__ );

		echo '<div class="updated" style="border-left-color: #3498db">
				<p>
					<img src="' . plugins_url( 'gambit-logo.png', __FILE__ ) . '" style="display: block; margin-bottom: 10px"/>
					<strong>' . sprintf( __( 'Thank you for activating %s!', GAMBIT_HERO_BOX ), $pluginData['Name'] ) . '</strong><br>' .

					__( 'Now just edit your pages and create a Hero Box element in Visual Composer.', GAMBIT_HERO_BOX ) . '<br>' .

					__( 'If you need any support, you can leave us a ticket in our support site. The link to our support site is listed in the plugin details for future reference.', GAMBIT_HERO_BOX ) . '<br>' .
					'<a href="http://support.gambit.ph?utm_source=' . urlencode( $pluginData['Name'] ) . '&utm_medium=activation_notice" class="gambit_ask_rate button button-default" style="margin: 10px 0;" target="_blank">' . __( 'Visit our support site', GAMBIT_HERO_BOX ) . '</a>' .
					'<br>' .
					'<em style="color: #999">' . __( 'This notice will go away in a moment', GAMBIT_HERO_BOX ) . '</em><br>
				</p>
			</div>';
	}


	/************************************************************************
	 * Activation instructions & CodeCanyon rating notices END
	 ************************************************************************/

        
	/**
	 * Creates our shortcode settings in Visual Composer
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function createShortcode() {
		if ( ! is_admin() ) {
			return;
		}
        
		vc_map( array(
		    "name" => __( 'Hero Box', GAMBIT_HERO_BOX ),
		    "base" => "hero_box",
			"icon" => plugins_url( 'vc-icon.png', __FILE__ ),
			"description" => __( 'Showcase your image in a hero box', GAMBIT_HERO_BOX ),
		    "params" => array(
			    array(
			        'type' => 'attach_image',
			        'heading' => __( 'Upload Image', GAMBIT_HERO_BOX ),
			        'param_name' => 'image',
			        'value' => '',
			        'description' => __( 'Select the image that you want to display.' , GAMBIT_HERO_BOX ),
			    ),
				array(
				    'type' => 'dropdown',
				    'heading' => __( 'Select the design of your Hero Box', GAMBIT_HERO_BOX ),
				    'param_name' => 'type',
				    'value' => $this->type,
			    ),
			    array(
			        'type' => 'textfield',
			        'heading' => __( 'Title' , GAMBIT_HERO_BOX ),
			        'param_name' => 'title',
			        'value' => 'My Hero Box',
			        'description' => __( 'Enter the title of your image here.', GAMBIT_HERO_BOX ),
			    ),
			    array(
			        'type' => 'textfield',
			        'heading' => __( 'Caption/Description', GAMBIT_HERO_BOX ),
			        'param_name' => 'caption',
			        'value' => 'Hero Box Description',
			        'description' => __( 'Enter a short caption of your image here.', GAMBIT_HERO_BOX ),
			    ),
			    array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Tint', GAMBIT_HERO_BOX ),
			        'param_name' => 'tint',
			        'value' => '#000000',
			        'description' => __( 'Tint your hero box with a cool color', GAMBIT_HERO_BOX ),
		        ),
		        array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Title Color', GAMBIT_HERO_BOX ),
			        'param_name' => 'font_color_title',
			        'value' => '',
			        'description' => __( 'Leave empty to use the default', GAMBIT_HERO_BOX ),
		        ),
		        array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Caption/Description Color', GAMBIT_HERO_BOX ),
			        'param_name' => 'font_color_caption',
			        'value' => '',
			        'description' => __( 'Leave empty to use the default', GAMBIT_HERO_BOX ),
		        ),
			    array(
			        'type' => 'textfield',
			        'heading' => __( 'Link', GAMBIT_HERO_BOX ),
			        'param_name' => 'link',
			        'value' => '',
			        'description' => __( 'Enter a link here to make your hero box clickable.', GAMBIT_HERO_BOX ),			        
			    ),
	        ),
		) );
	}
    
    Public function createShortcodeGallery() {
        if ( ! is_admin() ) {
			return;
		}
		
		vc_map( array(
		    "name" => __( 'Hero Box Gallery', GAMBIT_HERO_BOX ),
		    "base" => "hero_box_gallery",
			"icon" => plugins_url( 'vc-icon.png', __FILE__ ),
			"description" => __( 'Create multiple hero boxes', GAMBIT_HERO_BOX ),
		    "params" => array(
				array(
			        'type' => 'attach_images',
			        'heading' => __( 'Upload Images', GAMBIT_HERO_BOX ),
			        'param_name' => 'images',
			        'value' => '',
			        'description' => __( 'Select images that you want to display in Hero Boxes. Hero Box titles and captions will come from the title and descriptions from each image that you select.' , GAMBIT_HERO_BOX ),
			    ),
			    array(
				    'type' => 'dropdown',
				    'heading' => __( 'Select the design of your Hero Box', GAMBIT_HERO_BOX ),
				    'param_name' => 'type',
				    'value' => $this->type,
			    ),
			    array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Tint', GAMBIT_HERO_BOX ),
			        'param_name' => 'tint',
			        'value' => '#000000',
			        'description' => __( 'Tint your hero box with a cool color', GAMBIT_HERO_BOX ),
		        ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Columns', GAMBIT_HERO_BOX ),
			        'param_name' => 'column',
			        'value' => array(
			        	__( '1 Column', GAMBIT_HERO_BOX ) => '1',
			        	__( '2 Columns', GAMBIT_HERO_BOX ) => '2',
			        	__( '3 Columns', GAMBIT_HERO_BOX ) => '3',
			        ),
			        'description' => __( 'Choose whether to display your hero box in 1-3 columns.', GAMBIT_HERO_BOX ),
		        ),
	        ),
		) );
    }

	/**
	 * Shortcode logic
	 *
	 * @param	$atts array The attributes of the shortcode
	 * @param	$content string The content enclosed inside the shortcode if any
	 * @return	string The rendered html
	 * @since	1.0
	 */
	public function renderShortcode( $atts, $content = null ) {
        $atts = shortcode_atts( array(
			'type' => '',
			'image' => '',
			'title' => '',
			'caption' => '',
			'link' => '',
			'font_color_title' => '',
			'font_color_caption' => '',
			'tint' => '',
		), $atts );
        
        $ret = "";
        
            while ( $atts['type'] == 'random' ) {
                $randKey = array_rand( $this->type, 1 );
                $atts['type'] =  $this->type[ $randKey ];
            }
            
            $ret .= "<div class='hero-box hero-box-effect-" . esc_attr( $atts['type'] ) . "' style='background-color:" . esc_attr( $atts['tint'] ) . "; border-color:" . esc_attr( $atts['tint'] ) . "'>";
                $ret .= "<figure class='hero-box-wrapper'>";
                    $imageID = '';
                    if ( ! empty( $atts['image'] ) ) {
                        $imageID = $atts['image'];
                        if ( ! empty( $imageID ) ) {
                            $ret .= wp_get_attachment_image( $imageID, 'large' );
                        }    
                    }   
                    if ( ! empty( $atts['title'] ) || ! empty( $atts['caption'] ) || ! empty( $atts['link'] ) ) {
                        $ret .= "<figcaption>";
                            $ret .= "<div class='hero-box-text'>";
                                $fontColorTitle = '';
                                $fontColorCaption = '';
                                if ( ! empty( $atts['font_color_title'] ) ) {
                                    $fontColorTitle = " style='color:" . esc_attr( $atts['font_color_title'] ) . ";'";
                                }
                                if ( ! empty( $atts['title'] ) ) {
                                    $ret .= "<h3" . $fontColorTitle . ">" . esc_attr( $atts['title'] ) . "</h3>";
                                }
                                if ( ! empty( $atts['font_color_caption'] ) ) {
                                    $fontColorCaption = " style='color:" . esc_attr( $atts['font_color_caption'] ) . ";'";
                                }
                                if ( ! empty( $atts['caption'] ) ) {
                                    $ret .= "<p" . $fontColorCaption . ">" . esc_attr( $atts['caption'] ) . "</p>";
                                }
                                
                             $ret .= "</div>";
                         
                            if ( ! empty( $atts['link'] ) ) {
                                $ret .= "<a href='" . esc_url( $atts['link'] ) . "'>" . __( 'View more', GAMBIT_HERO_BOX ) . "</a>";
                            }
                       
                        $ret .= "</figcaption>";
                    }
                $ret .= "</figure>";
            
            $ret .= "</div>";
            
		wp_enqueue_style( __CLASS__, plugins_url( 'css/style.css', __FILE__ ), array(), VERSION_GAMBIT_HERO_BOX );
		wp_enqueue_script( __CLASS__, plugins_url( 'js/min/script-min.js', __FILE__ ), array( 'jquery' ), VERSION_GAMBIT_HERO_BOX, true );
		
		return $ret;
	}
	
	public function renderShortcodeGallery( $atts, $content = null ) {
        $atts = shortcode_atts( array(
			'type' => '',
			'images' => '',
			'tint' => '',
			'column' => '',
		), $atts );
		
		$images = explode( ',', $atts['images'] );
		
		$ret = '';
		
		foreach ( $images as $imageID ) {	
    	    $attachment = get_post($imageID);
    	    
    	    $link = get_post_meta( $imageID, '_hero-box-tile-link-to', true );
    	    
        	if ($attachment) {
    		    $image_title = $attachment->post_title;
    	        $caption = $attachment->post_excerpt;
    	    
                $ret .= "[hero_box image='" . esc_attr( $imageID ) . "' title='" . esc_attr( $image_title ) . "' caption='" . esc_attr( $caption ) . "' type='" . esc_attr( $atts['type'] ) . "' tint='" . esc_attr( $atts['tint'] ) . "' link='" . $link . "']";
            }
        }
        
        $ret = "<div class='hero-box-columns-" . $atts['column'] . "'>" . do_shortcode( $ret ) . "</div>";
	    
	    return $ret;
	}
}

new GambitHeroBox();

}