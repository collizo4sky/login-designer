<?php
/**
 * Plugin Name: Login Designer
 * Plugin URI: @@pkg.plugin_uri
 * Description: @@pkg.description
 * Author: @@pkg.author
 * Author URI: @@pkg.author_uri
 * Version: @@pkg.version
 * Text Domain: @@textdomain
 * Domain Path: languages
 * Requires at least: @@pkg.requires
 * Tested up to: @@pkg.tested_up_to
 *
 * @@pkg.name is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * @@pkg.name is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with @@pkg.name. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   @@pkg.name
 * @author    @@pkg.author
 * @license   @@pkg.license
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Login_Designer' ) ) :

	/**
	 * Main Login Designer Class.
	 *
	 * @since 1.0.0
	 */
	final class Login_Designer {
		/** Singleton *************************************************************/

		/**
		 * Login_Designer The one true Login_Designer
		 *
		 * @var string $instance
		 */
		private static $instance;

		/**
		 * Main Login_Designer Instance.
		 *
		 * Insures that only one instance of Login_Designer exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @static
		 * @static var array $instance
		 * @uses Login_Designer::constants() Setup the constants needed.
		 * @uses Login_Designer::init() Initiate actions and filters.
		 * @uses Login_Designer::includes() Include the required files.
		 * @uses Login_Designer::load_textdomain() load the language files.
		 * @see LOGIN_DESIGNER()
		 * @return object|Login_Designer The one true Login_Designer
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Login_Designer ) ) {
				self::$instance = new Login_Designer;
				self::$instance->constants();
				self::$instance->init();
				self::$instance->includes();
				self::$instance->load_textdomain();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', '@@textdomain' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', '@@textdomain' ), '1.0' );
		}

		/**
		 * Setup plugin constants.
		 *
		 * @access private
		 * @return void
		 */
		private function constants() {
			$this->define( 'LOGIN_DESIGNER_HAS_PRO', false );
			$this->define( 'LOGIN_DESIGNER_VERSION', '@@pkg.version' );
			$this->define( 'LOGIN_DESIGNER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'LOGIN_DESIGNER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'LOGIN_DESIGNER_PLUGIN_FILE', __FILE__ );
			$this->define( 'LOGIN_DESIGNER_ABSPATH', dirname( __FILE__ ) . '/' );
			$this->define( 'LOGIN_DESIGNER_CUSTOMIZE_CONTROLS_DIR', plugin_dir_path( __FILE__ ) . 'includes/controls/' );
			$this->define( 'LOGIN_DESIGNER_STORE_URL', 'https://logindesigner.com/' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string|string $name Name of the definition.
		 * @param  string|bool   $value Default value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Load the actions
		 *
		 * @return void
		 */
		public function init() {
			add_action( 'wp_head', array( $this, 'meta_version' ) );
			add_action( 'admin_init', array( $this, 'redirect_customizer' ) );
			add_action( 'admin_menu', array( $this, 'options_page' ) );
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_link' ), 999 );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
			add_filter( 'plugin_row_meta', array( $this, 'extension_plugin_row_meta' ), 10, 2 );
			add_filter( 'plugin_action_links_' . plugin_basename( LOGIN_DESIGNER_PLUGIN_DIR . 'login-designer.php' ), array( $this, 'plugin_action_links' ) );
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @since  1.0.0
		 * @return void
		 */
		private function includes() {

			if ( is_admin() ) {
				require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/admin/class-login-designer-license-handler.php';
				require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/admin/class-login-designer-extension-updater.php';
			}

			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/class-login-designer-customizer.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/class-login-designer-customizer-output.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/class-login-designer-customizer-scripts.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/class-login-designer-frontend-settings.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/class-login-designer-templates.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/class-login-designer-theme-template.php';
			require_once LOGIN_DESIGNER_PLUGIN_DIR . 'includes/install.php';
		}

		/**
		 * Add the plugin version to the header.
		 *
		 * @access public
		 * @return void
		 */
		public function meta_version() {
			echo '<meta name="generator" content="Login Designer ' . esc_attr( LOGIN_DESIGNER_VERSION ). '" />' . "\n";
		}

		/**
		 * Add a page under the "Apperance" menu, that links to the Customizer.
		 *
		 * @access public
		 * @return void
		 */
		public function options_page() {
			add_theme_page( esc_html__( 'Login Designer', '@@textdomain' ), esc_html__( 'Login Designer', '@@textdomain' ), 'manage_options', 'login-designer', '__return_null' );
		}

		/**
		 * Hook to redirect the page for the Customizer.
		 *
		 * @access public
		 * @return void
		 */
		public function redirect_customizer() {
			if ( ! empty( $_GET['page'] ) ) { // Input var okay.
				if ( 'login-designer' === $_GET['page'] ) { // Input var okay.

					// Pull the Login Designer page from options.
					$page = get_permalink( $this->get_login_designer_page() );

					wp_safe_redirect( admin_url( '/customize.php?autofocus[section]=login_designer__section--templates&url=' . $page ) );
				}
			}
		}

		/**
		 * Pull the Login Designer page from options.
		 *
		 * @access public
		 */
		public function get_login_designer_page() {

			$admin_options 	= get_option( 'login_designer_settings', array() );
			$page 		= array_key_exists( 'login_designer_page', $admin_options ) ? get_post( $admin_options['login_designer_page'] ) : false;

			return $page;
		}

		/**
		 * Pull the Login Designer page from options.
		 *
		 * @access public
		 */
		public function has_pro() {

			if ( true === LOGIN_DESIGNER_HAS_PRO ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Add admin bar link.
		 *
		 * @since	1.0.0
		 * @param	string|string $wp_admin_bar The admin bar.
		 */
		public function admin_bar_link( $wp_admin_bar ) {

			if ( ! is_page_template( 'template-login-designer.php' ) ) {
				return;
			}

			$args = array(
				'id' => 'login-designer',
				'title' => esc_html__( 'Login Designer', '@@textdomain' ),
				'href' => admin_url( '/customize.php?autofocus[section]=login_designer__section--templates&url='.home_url( '/login-designer' ) ),
				'meta' => array(
					'target' => '_self',
					'class' => 'login-designer-link',
					'title' => esc_html__( 'Login Designer', '@@textdomain' ),
				),
			);

			$wp_admin_bar->add_node( $args );

		}

		/**
		 * Returns the URL to upgrade the plugin to the pro version.
		 * Can be overridden by theme developers to use their affiliate
		 * link using the login_designer_affiliate_id filter.
		 *
		 * @since	1.0.0
		 * @return 	string
		 */
		public function get_affiliate_id() {

			$id = array( 'ref' => apply_filters( 'login_designer_affiliate_id', null ) );

			return $id;
		}

		/**
		 * Returns a URL that points to the Beaver Builder store.
		 *
		 * @since 1.0.0
		 * @param string|string $path A URL path to append to the store URL.
		 * @param array|array   $params An array of key/value params to add to the query string.
		 * @return string
		 */
		public function get_store_url( $path = '', $params = array() ) {

			$id = $this->get_affiliate_id();

			$params = array_merge( $params, $id );

			$url = trailingslashit( LOGIN_DESIGNER_STORE_URL . $path ) . '?' . http_build_query( $params, '', '&#038;' );

			return $url;
		}

		/**
		 * Add links to the settings page to the plugin.
		 *
		 * @param       array|array $actions The plugin.
		 * @return      array
		 */
		public function plugin_action_links( $actions ) {

			// Add the Settings link.
			$settings = array( 'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'themes.php?page=login-designer' ) , esc_html__( 'Settings', '@@textdomain' ) ) );

			// If there's no pro version, just return the settings link.
			if ( ! $this->has_pro() ) {

				return array_merge(
					$settings,
					$actions
				);
			}

			// Check if a license is valid. If it is, show the support link and remove the upgrade link.
			$license = new Login_Designer_License_Handler();

			if ( $license->is_valid_license() && $this->has_pro() ) {
				$title = esc_html__( 'Support', '@@textdomain' );
				$url = $this->get_store_url( 'support', array( 'utm_medium' => 'login-designer-pro', 'utm_source' => 'plugins-page', 'utm_campaign' => 'plugins-action-link', 'utm_content' => 'support' ) );

			} else {
				$title = esc_html__( 'Pro', '@@textdomain' );
				$url = $this->get_store_url( 'pricing', array( 'utm_medium' => 'login-designer-lite', 'utm_source' => 'plugins-page', 'utm_campaign' => 'plugins-action-link', 'utm_content' => 'pro' ) );
			}

			// Merge and display each link.
			return array_merge(
				$settings,
				array( 'url' => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $url ) , $title ) ),
				$actions
			);

			return array_merge( $settings, $actions );
		}

		/**
		 * Plugin row meta links
		 *
		 * @param array|array   $input already defined meta links.
		 * @param string|string $file plugin file path and name being processed.
		 * @return array $input
		 */
		public function plugin_row_meta( $input, $file ) {

			// Return early, if a pro version is not available.
			if ( ! Login_Designer()->has_pro() ) {
				return $input;
			}

			if ( 'login-designer/login-designer.php' !== $file ) {
				return $input;
			}

			$extensions_url = $this->get_store_url( 'extensions', array( 'utm_medium' => 'login-designer-lite', 'utm_source' => 'plugins-page', 'utm_campaign' => 'plugin-row', 'utm_content' => 'extensions' ) );

			$links = array(
				'<a href="' . esc_url( $extensions_url ) . '" target="_blank">' . esc_html__( 'Extensions', '@@textdomain' ) . '</a>',
			);

			$input = array_merge( $input, $links );

			return $input;
		}

		/**
		 * Plugin row meta links for extensions.
		 *
		 * @param array|array   $input already defined meta links.
		 * @param string|string $file plugin file path and name being processed.
		 * @return array $input
		 */
		public function extension_plugin_row_meta( $input, $file ) {

			// Return early, if a pro version is not available.
			if ( ! Login_Designer()->has_pro() ) {
				return $input;
			}

			// Return early, if the file name does not contain "login-designer-", which is standard for extenstions.
			if ( strpos( $file, 'login-designer-' ) === false ) {
				return $input;
			}

			// Get the plugin name, so we can view the analytics properly.
			$plugin_name = substr( $file, 0, strpos( $file, '/' ) );

			$extensions_url = $this->get_store_url( 'extensions', array( 'utm_medium' => $plugin_name, 'utm_source' => 'plugins-page', 'utm_campaign' => 'plugin-row', 'utm_content' => 'extensions' ) );

			$links = array(
				'<a href="' . esc_url( $extensions_url ) . '" target="_blank">' . esc_html__( 'Extensions', '@@textdomain' ) . '</a>',
			);

			$input = array_merge( $input, $links );

			return $input;
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( '@@textdomain', false, dirname( plugin_basename( LOGIN_DESIGNER_PLUGIN_DIR ) ) . '/languages/' );
		}
	}

endif; // End if class_exists check.

/**
 * The main function for that returns Login_Designer
 *
 * The main function responsible for returning the one true Login_Designer
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $login_designer = login_designer(); ?>
 *
 * @since 1.0.0
 * @return object|Login_Designer The one true Login_Designer Instance.
 */
function login_designer() {
	return Login_Designer::instance();
}

// Get Login_Designer Running.
login_designer();
