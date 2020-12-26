<?php

namespace Ksps\Resume;

/**
 * Installer class
 */
class Installer {

	/**
	 * Run the installer
	 *
	 * @return void
	 */
	public function run() {
		$this->add_version();
		$this->create_tables();
	}

	/**
	 * Add time and version on DB
	 */
	public function add_version() {
		$installed = get_option( 'ksp_resume_installed' );

		if ( ! $installed ) {
			update_option( 'ksp_resume_installed', time() );
		}

		update_option( 'ksp_resume_version', KSPS_RESUME_VERSION );
	}

	/**
	 * Create necessary database tables
	 *
	 * @return void
	 */
	public function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}applicant_submissions` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `first_name` varchar(100) NOT NULL DEFAULT '',
    	  `last_name` varchar(100) NOT NULL DEFAULT '',
          `present_address` varchar(255) DEFAULT NULL,
          `email` varchar(100) NOT NULL DEFAULT '',
          `phone` varchar(30) DEFAULT NULL,
    	  `post_name` varchar(100) DEFAULT NULL,
    	  `cv` varchar(100) DEFAULT NULL,
          `created_by` bigint(20) unsigned NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}
}