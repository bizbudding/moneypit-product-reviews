<?php

/**
 * Plugin Name:     Money Pit Product Reviews
 * Plugin URI:      https://moneypit.com
 * Description:     Create rich product review lists.
 * Version:         0.8.0
 *
 * Author:          BizBudding, Mike Hemberger
 * Author URI:      https://bizbudding.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main MP_Product_Reviews_Plugin Class.
 *
 * @since 0.1.0
 */
final class MP_Product_Reviews_Plugin {

	/**
	 * @var   MP_Product_Reviews_Plugin The one true MP_Product_Reviews_Plugin
	 * @since 0.1.0
	 */
	private static $instance;

	/**
	 * Main MP_Product_Reviews_Plugin Instance.
	 *
	 * Insures that only one instance of MP_Product_Reviews_Plugin exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   0.1.0
	 * @static  var array $instance
	 * @uses    MP_Product_Reviews_Plugin::setup_constants() Setup the constants needed.
	 * @uses    MP_Product_Reviews_Plugin::includes() Include the required files.
	 * @uses    MP_Product_Reviews_Plugin::hooks() Activate, deactivate, etc.
	 * @see     MP_Product_Reviews_Plugin()
	 * @return  object | MP_Product_Reviews_Plugin The one true MP_Product_Reviews_Plugin
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the setup.
			self::$instance = new MP_Product_Reviews_Plugin;
			// Methods.
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'moneypit-product-reviews' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'moneypit-product-reviews' ), '1.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'MP_PRODUCT_REVIEWS_VERSION' ) ) {
			define( 'MP_PRODUCT_REVIEWS_VERSION', '0.8.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MP_PRODUCT_REVIEWS_PLUGIN_DIR' ) ) {
			define( 'MP_PRODUCT_REVIEWS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Includes Path.
		// if ( ! defined( 'MP_PRODUCT_REVIEWS_INCLUDES_DIR' ) ) {
		// 	define( 'MP_PRODUCT_REVIEWS_INCLUDES_DIR', MP_PRODUCT_REVIEWS_PLUGIN_DIR . 'includes/' );
		// }

		// Plugin Classes Path.
		if ( ! defined( 'MP_PRODUCT_REVIEWS_CLASSES_DIR' ) ) {
			define( 'MP_PRODUCT_REVIEWS_CLASSES_DIR', MP_PRODUCT_REVIEWS_PLUGIN_DIR . 'classes/' );
		}

		// Plugin Folder URL.
		if ( ! defined( 'MP_PRODUCT_REVIEWS_PLUGIN_URL' ) ) {
			define( 'MP_PRODUCT_REVIEWS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'MP_PRODUCT_REVIEWS_PLUGIN_FILE' ) ) {
			define( 'MP_PRODUCT_REVIEWS_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Base Name
		if ( ! defined( 'MP_PRODUCT_REVIEWS_BASENAME' ) ) {
			define( 'MP_PRODUCT_REVIEWS_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
		}
	}

	/**
	 * Include required files.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function includes() {
		// Include vendor libraries.
		require_once __DIR__ . '/vendor/autoload.php';
		// Includes.
		// foreach ( glob( MP_PRODUCT_REVIEWS_INCLUDES_DIR . '*.php' ) as $file ) { include $file; }
		// Classes.
		foreach ( glob( MP_PRODUCT_REVIEWS_CLASSES_DIR . '*.php' ) as $file ) { include $file; }
		// Instantiate.
		$register = new MP_Product_Reviews_Register;
		$block    = new MP_Product_Reviews_Block;
	}

	/**
	 * Run the hooks.
	 *
	 * @since   0.1.0
	 * @return  void
	 */
	public function hooks() {
		add_action( 'admin_init', [ $this, 'updater' ] );
	}

	/**
	 * Setup the updater.
	 *
	 * composer require yahnis-elsts/plugin-update-checker
	 *
	 * @since 0.1.0
	 *
	 * @uses https://github.com/YahnisElsts/plugin-update-checker/
	 *
	 * @return void
	 */
	public function updater() {
		// Bail if current user cannot manage plugins.
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		// Bail if plugin updater is not loaded.
		if ( ! class_exists( 'Puc_v4_Factory' ) ) {
			return;
		}

		// Setup the updater.
		$updater = Puc_v4_Factory::buildUpdateChecker( 'https://github.com/bizbudding/moneypit-product-reviews/', __FILE__, 'moneypit-product-reviews' );

		// Maybe set github api token.
		if ( defined( 'MAI_GITHUB_API_TOKEN' ) ) {
			$updater->setAuthentication( MAI_GITHUB_API_TOKEN );
		}
	}
}

/**
 * The main function for that returns MP_Product_Reviews_Plugin
 *
 * The main function responsible for returning the one true MP_Product_Reviews_Plugin
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $plugin = MP_Product_Reviews_Plugin(); ?>
 *
 * @since 0.1.0
 *
 * @return object|MP_Product_Reviews_Plugin The one true MP_Product_Reviews_Plugin Instance.
 */
function mp_product_reviews_plugin() {
	return MP_Product_Reviews_Plugin::instance();
}

// Get MP_Product_Reviews_Plugin Running.
mp_product_reviews_plugin();
