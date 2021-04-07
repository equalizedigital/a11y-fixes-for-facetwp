<?php
/*
Plugin Name: Accessibility fixes for FacetWP
Description: This plugin fixes accessibility issues related to the FacetWP plugin  
Version: 1.0
Author: Equalize Digital
Author URI: https://equalizedigital.com/

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

defined( 'ABSPATH' ) or exit;

define( 'A11Y_FOR_FWP_CORE_DIR' , plugin_dir_path( __FILE__ ) );
define( 'A11Y_FOR_FWP_CORE_URL' , plugin_dir_url(__FILE__ ) );


/**
 * Hooks to setup plugin
 */
add_action( 'plugins_loaded', 'a11y_for_fwp_bootstrap', 25 );
/**
 * Load plugin or throw notice
 *
 * @uses plugins_loaded
 */
function a11y_for_fwp_bootstrap(){
	$php_check = version_compare( PHP_VERSION, '7.0', '>=' );
	if ( ! class_exists( 'FacetWP' ) ) {
		function a11y_for_fwp_notice() {
			global $pagenow;
			if( 'plugins.php' !== $pagenow ) {
				return;
			}
			?>
			<div class="notice notice-error">
				<p><?php _e( 'Accessibility Fixes for FacetWP requires FacetWP to be installed for this plugin to function', 'a11y-for-fwp' ); ?></p>
			</div>
			<?php
		}
		add_action( 'admin_notices', 'a11y_for_fwp_notice' );
	}else{
		//bootstrap plugin
		require_once( dirname( __FILE__ ) . '/bootstrap.php' );
	}
}
