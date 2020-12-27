<?php

/**
 * Insert a new resume
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function ksps_resume_insert_resume( $args = [] ) {
	global $wpdb;

	if ( empty( $args['first_name'] ) ) {
		return new \WP_Error( 'no-first-name', __( 'You must provide first name.', 'ksps-resume' ) );
	}

	if ( empty( $args['last_name'] ) ) {
		return new \WP_Error( 'no-last-name', __( 'You must provide last name.', 'ksps-resume' ) );
	}

	if ( empty( $args['email'] ) ) {
		return new \WP_Error( 'no-email', __( 'You must provide a email.', 'ksps-resume' ) );
	}

	if ( empty( $args['post_name'] ) ) {
		return new \WP_Error( 'no-post-name', __( 'You must provide post name.', 'ksps-resume' ) );
	}

	if ( empty( $args['phone'] ) ) {
		return new \WP_Error( 'no-phone', __( 'You must provide Mobile No.', 'ksps-resume' ) );
	}

	if ( empty( $args['present_address'] ) ) {
		return new \WP_Error( 'no-present-address', __( 'You must provide Present Address.', 'ksps-resume' ) );
	}

	$defaults = [
		'first_name'       => '',
		'last_name'       => '',
		'present_address'    => '',
		'email'    => '',
		'phone'      => '',
		'post_name'      => '',
		'cv'      => '',
		'created_by' => get_current_user_id(),
		'created_at' => current_time( 'mysql' ),
	];

	$data = wp_parse_args( $args, $defaults );

	if ( isset( $data['id'] ) ) {

		$id = $data['id'];
		unset( $data['id'] );

		$updated = $wpdb->update(
			$wpdb->base_prefix . 'applicant_submissions',
			$data,
			[ 'id' => $id ],
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s'
			],
			[ '%d' ]
		);

		ksps_resume_purge_cache( $id );

		return $updated;

	} else {

		$inserted = $wpdb->insert(
			$wpdb->base_prefix . 'applicant_submissions',
			$data,
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s'
			]
		);

		if ( ! $inserted ) {
			return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'ksps-resume' ) );
		}

		ksps_resume_purge_cache();

		return $wpdb->insert_id;
	}
}

/**
 * Fetch Resumes
 *
 * @param  array  $args
 *
 * @return array
 */
function ksps_resume_get_resumes( $args = [] ) {
	global $wpdb;

	$defaults = [
		'number'  => 20,
		'offset'  => 0,
		'orderby' => 'id',
		'order'   => 'ASC',
		's'   => ''
	];

	$args = wp_parse_args( $args, $defaults );

	$last_changed = wp_cache_get_last_changed( 'resume' );
	$key          = md5( serialize( array_diff_assoc( $args, $defaults ) ) );
	$cache_key    = "all:$key:$last_changed";

	$search = '';
	if ( ! empty( $args['s'] ) ) {
		$search = "WHERE first_name LIKE '%" . esc_sql($wpdb->esc_like( $_REQUEST['s'] )) . "%' 
		OR last_name LIKE '%" . esc_sql($wpdb->esc_like( $_REQUEST['s'] )) . "%' 
		OR post_name LIKE '%" . esc_sql($wpdb->esc_like( $_REQUEST['s'] )) . "%'
		OR email LIKE '%" . esc_sql($wpdb->esc_like( $_REQUEST['s'] )) . "%'
		";
	}

	$sql = "SELECT * FROM {$wpdb->base_prefix}applicant_submissions
    		{$search}" . $wpdb->prepare("ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %d, %d", $args['offset'], $args['number'] );

	$items = wp_cache_get( $cache_key, 'resume' );

	if ( false === $items ) {
		$items = $wpdb->get_results( $sql );

		wp_cache_set( $cache_key, $items, 'resume' );
	}

	return $items;
}

/**
 * Get the count of total resumes
 *
 * @return int
 */
function ksps_resume_resumes_count() {
	global $wpdb;

	$count = wp_cache_get( 'count', 'resume' );

	if ( false === $count ) {
		$count = (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->base_prefix}applicant_submissions" );

		wp_cache_set( 'count', $count, 'resume' );
	}

	return $count;
}

/**
 * Fetch a single resume from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function ksps_resume_get_resume( $id ) {
	global $wpdb;

	$resume = wp_cache_get( 'book-' . $id, 'resume' );

	if ( false === $resume ) {
		$resume = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}applicant_submissions WHERE id = %d", $id )
		);

		wp_cache_set( 'resume-' . $id, $resume, 'resume' );
	}

	return $resume;
}

/**
 * Delete an resume
 *
 * @param  int $id
 *
 * @return int|boolean
 */
function ksps_resume_delete_resume( $id ) {
	global $wpdb;

	ksps_resume_purge_cache( $id );

	return $wpdb->delete(
		$wpdb->base_prefix . 'applicant_submissions',
		[ 'id' => $id ],
		[ '%d' ]
	);
}

/**
 * Purge the cache for books
 *
 * @param  int $resume_id
 *
 * @return void
 */
function ksps_resume_purge_cache( $resume_id = null ) {
	$group = 'resume';

	if ( $resume_id ) {
		wp_cache_delete( 'resume-' . $resume_id, $group );
	}

	wp_cache_delete( 'count', $group );
	wp_cache_set( 'last_changed', microtime(), $group );
}