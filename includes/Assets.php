<?php

namespace Ksps\Resume;

/**
 * Assets handlers class
 */
class Assets {

	/**
	 * Class constructor
	 */
	function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
	}

	/**
	 * All available scripts
	 *
	 * @return array
	 */
	public function get_scripts() {
		return [
			'ksps-resume-script' => [
				'src'     => KSPS_RESUME_ASSETS . '/js/frontend.js',
				'version' => filemtime( KSPS_RESUME_PATH . '/assets/js/frontend.js' ),
				'deps'    => [ 'jquery' ]
			],
			'ksps-resume-admin-script' => [
				'src'     => KSPS_RESUME_ASSETS . '/js/admin.js',
				'version' => filemtime( KSPS_RESUME_PATH . '/assets/js/admin.js' ),
				'deps'    => [ 'jquery', 'wp-util' ]
			],
		];
	}

	/**
	 * All available styles
	 *
	 * @return array
	 */
	public function get_styles() {
		return [
			'ksps-resume-style' => [
				'src'     => KSPS_RESUME_ASSETS . '/css/frontend.css',
				'version' => filemtime( KSPS_RESUME_PATH . '/assets/css/frontend.css' )
			],
			'ksps-resume-admin-style' => [
				'src'     => KSPS_RESUME_ASSETS . '/css/admin.css',
				'version' => filemtime( KSPS_RESUME_PATH . '/assets/css/admin.css' )
			],
			'ksps-resume-widget-style' => [
				'src'     => KSPS_RESUME_ASSETS . '/css/widget.css',
				'version' => filemtime( KSPS_RESUME_PATH . '/assets/css/widget.css' )
			],
		];
	}

	/**
	 * Register scripts and styles
	 *
	 * @return void
	 */
	public function register_assets() {
		$scripts = $this->get_scripts();
		$styles  = $this->get_styles();

		foreach ( $scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;

			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $deps, $style['version'] );
		}

		wp_localize_script( 'ksps-resume-script', 'KspsResume', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'error'   => __( 'Something went wrong', 'ksps-resume' ),
		] );

		wp_localize_script( 'ksps-resume-admin-script', 'KspsResumeAdmin', [
			'nonce' => wp_create_nonce( 'ksps-resume-admin-nonce' ),
			'confirm' => __( 'Are you sure?', 'ksps-resume' ),
			'error' => __( 'Something went wrong', 'ksps-resume' ),
		] );
	}
}