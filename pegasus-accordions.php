<?php 
/*
Plugin Name: Pegasus Accordion Plugin
Plugin URI:	 https://developer.wordpress.org/plugins/the-basics/
Description: This allows you to create accordions on your website with just a shortcode.
Version:	 1.0
Author:		 Jim O'Brien
Author URI:	 https://visionquestdevelopment.com/
License:	 GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

	global $wpdb; 
	
	add_action("admin_menu", "pegasus_accordions_menu_item");
	function pegasus_accordions_menu_item() {
		//add_menu_page("Accordions", "Accordions", "manage_options", "pegasus_accordions_plugin_options", "pegasus_accordions_plugin_settings_page", null, 99);
	}
	
	function pegasus_accordions_plugin_settings_page() { ?>
		<div class="wrap pegasus-wrap">
			<h1>Accordion</h1>
		
			<p>Usage: <pre>[accordions][accordion class="first" title="Home"]Vivamus suscipit tortor eget felis porttitor volutpat. [/accordion][accordion class="second" title="Profile"]Pellentesque in ipsum id orci porta dapibus. [/accordion][/accordions]</pre> </p>
		
		</div>
	<?php
	}
	
	
	function pegasus_accordions_plugin_styles() {

		wp_register_style( 'accordions-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/accordions.css', array(), null, 'all' );

	}
	add_action( 'wp_enqueue_scripts', 'pegasus_accordions_plugin_styles' );
	
	/**
	* Proper way to enqueue JS 
	*/
	function pegasus_accordions_plugin_js() {
		
		wp_register_script( 'pegasus-accordions-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, 'all' );
		
	} //end function
	add_action( 'wp_enqueue_scripts', 'pegasus_accordions_plugin_js' );
	
	/**
	* accordions Short Code
	*/
	
	if ( ! class_exists( 'AccordionsClass' ) ) {
		class AccordionsClass {

			protected $_accordions_divs;

			public function __construct($accordions_divs = '') {
				$this->_accordions_divs = $accordions_divs;
				add_shortcode( 'accordions', array( $this, 'accordions_wrap') );
				add_shortcode( 'accordion', array( $this,'accordions_block') );
			}

			function accordions_wrap( $args, $content = null ) {
				$output = '<div class="accordion">' . do_shortcode($content);

				
				$output .= $this->_accordions_divs;
				
				$output .= '</div>'; //end pegasus-Accordions


				wp_enqueue_style( 'accordions-css' );
				wp_enqueue_script( 'pegasus-accordions-plugin-js' );

				return $output;
			}

			function accordions_block( $args, $content = null ) {
				extract(shortcode_atts(array(
					'id' => '',
					'title' => '',
					'class' => '',
				), $args));

				if ( '' === $id ) {
					$id = 1;
				}

				$output = '
					<div class="accordion-item ' . $class . '">
						<button id="accordion-button-' . $id . '" aria-expanded="false">
							<span class="accordion-title">' . $title . '</span>
							<span class="icon" aria-hidden="true"></span>
						</button>
						<section id="accordion-' . $id . '" class="accordion-panel accordion-content"><p>' . $content . '</p></section>
					</div>
				';

				$id++;

				return $output;
			}

		}
		new AccordionsClass;
	}
	
	?>