<?php

namespace Ksps\Resume\Admin;

/**
 * The Menu handler class
 */
class Menu {

    public $application;

    /**
     * Initialize the class
     */
    function __construct( $application ) {
        $this->application = $application;

        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function admin_menu() {
        $parent_slug = 'ksps-resume';
        $capability = 'manage_options';

        $hook = add_menu_page( __( 'Applicant Resume/CV Uploder', 'ksps-resume' ), __( 'Resume/CV', 'ksps-resume' ), 'manage_options', 'ksps-resume', [ $this->application, 'plugin_page' ], 'dashicons-businessman' );
        add_submenu_page( $parent_slug, __( 'Resume', 'ksps-resume' ), __( 'Resume', 'ksps-resume' ), $capability, $parent_slug, [ $this->application, 'plugin_page' ] );

        add_action( 'admin_head-' . $hook, [ $this, 'enqueue_assets' ] );
        
    }

	/**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'ksps-resume-admin-style' );
        wp_enqueue_script( 'ksps-resume-admin-script' );
    }

}
