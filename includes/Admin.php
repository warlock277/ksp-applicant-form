<?php

namespace Ksps\Resume;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    function __construct() {
	    $application = new Admin\Application();

	    $this->dispatch_actions( $application );

	    new Admin\Menu( $application );
	    new Admin\Widget();
    }

	/**
	 * Dispatch and bind actions
	 *
	 * @return void
	 */
	public function dispatch_actions( $application ) {

		add_action( 'admin_init', [ $application, 'form_handler' ] );
		add_action( 'wp_ajax_ksps-resume-delete-application', [ $application, 'delete_application' ] );

	}
}
