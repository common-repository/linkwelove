<?php
/*
Plugin Name: Linkwelove
Plugin URI: http://www.linkwelove.com
Description: A plugin to add widgets created with linkwelove
Version: 1.4.0
Author: webandtech.it, infowebandtech.it
Author URI: http://www.webandtech.it
License: GPL2
*/

/*  Copyright 2017  Web and Tech  (email : info@webandtech.it)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?><?php

// some definition we will use
define( 'LINKWELOVE_PUGIN_NAME', 'LINKWELOVE Plugin');
define( 'LINKWELOVE_PLUGIN_DIRECTORY', 'linkwelove');
define( 'LINKWELOVE_CURRENT_VERSION', '1.4.0' );
define( 'LINKWELOVE_REQUEST_URL', 'http://linkwelove.it/service.php'); 
define( 'LINKWELOVE_DEBUG', false); 

define( 'EMU2_I18N_DOMAIN', 'linkwelove' );
define( 'LINKWELOVE_MULTIPLE_W', true);

//------------

class Linkwelove {
	
	var $pluginPath;
	var $pluginUrl;
	
	// url to Linkwelove API (next release)
	//var $apiUrl = LINKWELOVE_REQUEST_URL;
	
	var $widgets;
	var $options;
	
	
	public function __construct()
	{
		// Set Plugin Path
		$this->pluginPath = dirname(__FILE__);
		
		// Set Plugin URL
		$this->pluginUrl = WP_PLUGIN_URL . '/linkwelove/';
		
		$this->widgets = get_option('linkwelove_widgets');
		
		
		$this->set_lang_file();
		//add_action('plugins_loaded', array($this, 'wt_load_textdomain'));
		
		register_activation_hook(__FILE__, array($this, 'activate') );
		register_deactivation_hook(__FILE__, array($this, 'deactivate') );
		register_uninstall_hook(__FILE__, array(__CLASS__, 'uninstall') );
		
		add_action( 'admin_head', array($this, 'register_style') );
		
		// create custom plugin settings menu
		add_action( 'admin_menu', array($this, 'create_menu') );
		
		//call register settings function
		add_action( 'admin_init', array($this, 'register_settings') );
		
		add_action( 'widgets_init', array($this, 'widgets_init') );

		add_action( 'init', array($this, 'shortcode_init') );

		add_action( 'wp_enqueue_scripts', array($this, 'css_inline') );

		add_action( 'wp_footer', array($this, 'lwljs') );
		
		add_filter("the_content", array( $this, 'addToContent'));
		
		add_option('linkwelove_css', '');
	}
	
	public function register_style() {
		wp_register_style( 'linkwelove_css', $this->pluginUrl.'css/linkwelove.css', null, '1.0', 'screen' );
		wp_enqueue_style( 'linkwelove_css' );
	}
	
	// load language files
	public function set_lang_file() {
		# set the language file
		$currentLocale = get_locale();
		if(!empty($currentLocale)) {
			$moFile = $this->pluginPath . "/lang/" . $currentLocale . ".mo";
			if (@file_exists($moFile) && is_readable($moFile)) {
				load_textdomain(EMU2_I18N_DOMAIN, $moFile);
			}
		}
	}
	public function wt_load_textdomain() {
		load_plugin_textdomain( 'linkwelove', false, $this->pluginUrl . '/lang/' );
	}
	
	// activating the default values
	public function activate() {
		
		add_option('linkwelove_enabled', '');
		add_option('linkwelove_widgets', '');
		
		
		
	}
	
	// deactivating
	public function deactivate() {
	}
	
	// uninstalling
	public static function uninstall() {
		// delete all data stored
		delete_option('linkwelove_enabled');
		delete_option('linkwelove_widgets', '');
		
		if (method_exists($this, 'deleteLogFolder')) $this->deleteLogFolder();
	}
	
	//create Linkwelove Menu
	public function create_menu() {
	
		// create new top-level menu
		add_menu_page(
			__('Linkwelove', EMU2_I18N_DOMAIN),
			__('Linkwelove', EMU2_I18N_DOMAIN),
			'edit_pages',
			LINKWELOVE_PLUGIN_DIRECTORY.'/linkwelove_settings.php',
			'',
			$this->pluginUrl.'images/icon.png'
		);
	
	
		add_submenu_page(
			LINKWELOVE_PLUGIN_DIRECTORY.'/linkwelove_settings.php',
			__("Settings", EMU2_I18N_DOMAIN),
			__("Settings", EMU2_I18N_DOMAIN),
			'edit_pages',
			LINKWELOVE_PLUGIN_DIRECTORY.'/linkwelove_settings.php'
		);
		
		$page = '';//add_submenu_page(LINKWELOVE_PLUGIN_DIRECTORY.'/linkwelove_settings.php', __("Bulk optimize", EMU2_I18N_DOMAIN), __("Bulk optimize", EMU2_I18N_DOMAIN), 'manage_options', 'bulk_page_slug', array($this, 'bulk_page_handler') );
		add_action('admin_print_scripts-' . $page, array($this, 'bulk') );
		
		
	}
	
	
	public function register_settings() {
		//register settings
		register_setting( 'linkwelove-settings-group', 'linkwelove_widgets');		
		register_setting( 'linkwelove-settings-group', 'linkwelove_enabled' );
		register_setting( 'linkwelove-settings-group', 'linkwelove_css');
	}
	public static function createCodeLwl($cod) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( is_plugin_active( 'amp/amp.php' ) && is_amp_endpoint() ) {
			add_action( 'amp_post_template_head', 'amp_iframewt' );
			if ( ! isset( $checkFunctExist ) ) {
				function amp_iframewt() {
					global $checkFunctExist;
					?>
					<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
					<?php
				}
			}

			$add = "
			<amp-iframe
			   	width=\"100\" height=\"100\"
			    sandbox=\"allow-top-navigation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox\"
			    layout=\"responsive\"
			    frameborder=\"0\"
			    resizable
			    src=\"https://w.linkwelove.it/amp/?id=".$cod."\">
			    <div overflow tabindex=0 role=button aria-label=\"LWL\">Lwl</div>
			</amp-iframe>
			";
		} else {
		$lwl_index = ', {noindex:true}';
			if(((is_page())and(!is_404())) or (is_single()and(!is_404())) and(!is_search())and( !is_attachment() ) ){
				$lwl_index = '';
			}				
			$add = "
				<div id=\"lwl_".$cod."\"></div>
				<!-- linkwelove wp vers.".LINKWELOVE_CURRENT_VERSION."-->
				<script type=\"text/javascript\">
				var _lwl = _lwl || [];
				_lwl.push(['id', '".$cod."'".$lwl_index."]);
				</script>
				";
		}
		return $add;
	}
	public static function addToContent($content) {
		
		$add_before = '';
		$add_after = '';
		
		//if((is_single()or is_page())and(!is_front_page())){
		if(is_single() or is_page() ){
		
			$opt_lwlwidget = get_option('linkwelove_widgets');
			if (!empty( $opt_lwlwidget ) ){
				foreach ($opt_lwlwidget as $kw => $vw){
					
					if( isset( $vw['act'] ) && 1 == $vw['act'] && (
							(is_single() && isset($vw['vis']['art']))
						||  (is_page() && isset($vw['vis']['pag']))
						||  (is_archive() && isset($vw['vis']['arc']))
						)){
						
						
					
						$add = LinkWeLove::createCodeLwl($vw['cod']);
					
						if($vw['pos']==1){
							$add_after .= $add;
						}else if($vw['pos']==2){
							$add_before .= $add;
						}
					}
				}
			}
		}
		
		
		return $add_before.$content.$add_after;
		
	}




	public static function widgets_init() {
		/*$num_ist = 0;
		if (is_array(get_option('linkwelove_widgets'))){
			foreach (get_option('linkwelove_widgets') as $kw => $vw){
				if((isset($vw['pos']))and($vw['pos']==3)and(isset($vw['act']))and($vw['act']==1)){
					$num_ist++;
				}
			}
		}
		if($num_ist==0){
			//unregister_widget('linkwelove_widget');
		}
		else {
			register_widget('linkwelove_widget');
		}*/
		register_widget('linkwelove_widget');
	}

	public static function shortcode_init() {
		add_shortcode('lwlWidget', 'linkwelove_shortcode_function');
		function linkwelove_shortcode_function($atts){
		   extract(shortcode_atts(array(
		      'cod' => '',
		   ), $atts));

		   $return_string = LinkWeLove::createCodeLwl($cod);
		   return $return_string;
		}

	}
	
	public static function css_inline() {
		if (get_option('linkwelove_css')&&get_option('linkwelove_css')!=''){
			wp_enqueue_style(
				'custom-style',
				WP_PLUGIN_URL . '/linkwelove/css/lwlcustom.css'
			);
			$custom_css = get_option('linkwelove_css');
        	wp_add_inline_style( 'custom-style', $custom_css );
		}
	}

	public static function lwljs() {
		?>
			<script type="text/javascript">
				console.log('lwljs');
				var _lwl = _lwl || [];
				(function() {
					var lwl = document.createElement('script'); lwl.type = 'text/javascript'; lwl.async = true;
					lwl.src = '//sd.linkwelove.com/widget/js/lwl.js';
					var lwls = document.getElementsByTagName('script')[0]; lwls.parentNode.insertBefore(lwl, lwls);
				})();
				</script>
		<?php
	}
	
}

$wpLinkwelove = new Linkwelove();


include_once ( "widget.class.php" );
//----------------

// Filter Functions with Hooks
function lwlcustom_mce_button() {
  // Check if user have permission
  if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
    return;
  }
  // Check if WYSIWYG is enabled
  if ( 'true' == get_user_option( 'rich_editing' ) ) {
    add_filter( 'mce_external_plugins', 'lwlcustom_tinymce_plugin' );
    add_filter( 'mce_buttons', 'register_lwlmce_button' );
  }
}
add_action('admin_head', 'lwlcustom_mce_button');



// Function for new button
function lwlcustom_tinymce_plugin( $plugin_array ) {



  $plugin_array['lwlcustom_mce_button'] = WP_PLUGIN_URL . '/linkwelove/js/editorShortcode.js';
  return $plugin_array;
}


// Register new button in the editor
function register_lwlmce_button( $buttons ) {
  array_push( $buttons, 'lwlcustom_mce_button' );
  return $buttons;
}