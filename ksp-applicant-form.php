<?php
/**
 * Plugin Name: Applicant Resume/CV Uploder
 * Description: Applicants CV/Resume Uploader Plugin
 * Plugin URI: https://github.com/warlock277
 * Author: Kazi Shiplu
 * Author URI: https://github.com/warlock277
 * Version: 1.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Ksps_Resume {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class constructor
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Ksps_Resume
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'KSPS_RESUME_VERSION', self::version );
        define( 'KSPS_RESUME_FILE', __FILE__ );
        define( 'KSPS_RESUME_PATH', __DIR__ );
        define( 'KSPS_RESUME_URL', plugins_url( '', KSPS_RESUME_FILE ) );
        define( 'KSPS_RESUME_ASSETS', KSPS_RESUME_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

    	new Ksps\Resume\Assets();

    	if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		    new Ksps\Resume\Ajax();
	    }

        if ( is_admin() ) {
            new Ksps\Resume\Admin();
        } else {
            new Ksps\Resume\Frontend();
        }

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {

	    $installer = new Ksps\Resume\Installer();
	    $installer->run();

    }
}

/**
 * Initializes the main plugin
 *
 * @return \Ksps_Resume
 */
function Ksps_Resume() {
    return Ksps_Resume::init();
}

ksps_resume();
