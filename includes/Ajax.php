<?php

namespace Ksps\Resume;

class Ajax {

	/**
	 * Constructor
	 */
	function __construct() {

		add_action( 'wp_ajax_ksps_resume_form_submit', [ $this, 'submit_application'] );
		add_action( 'wp_ajax_nopriv_ksps_resume_form_submit', [ $this, 'submit_application'] );
		add_action( 'wp_ajax_ksps-resume-delete-submission', [ $this, 'delete_submission'] );
	}

	/**
	 * Handle Enquiry Submission
	 *
	 * @return void
	 */
	public function submit_application() {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'ksps-resume-form' ) ) {
			wp_send_json_error( [
				'message' => __( 'Nonce verification failed!', 'ksps-resume-form' )
			] );
		}

		$errors = '';

		$id                 = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$first_name         = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
		$last_name          = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
		$email              = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$present_address    = isset( $_POST['present_address'] ) ? sanitize_textarea_field( $_POST['present_address'] ) : '';
		$phone              = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
		$post_name          = isset( $_POST['post_name'] ) ? sanitize_text_field( $_POST['post_name'] ) : '';
		$file               = isset( $_FILES['cv'] ) ? $_FILES['cv'] : '';
		$cv                 = '';

		if( !empty($file) ) {

			/**
			 * Check for accepted file_types
			 */
			$file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

			if( !in_array( $file_extension, ['pdf', 'doc', 'docx' ]) ) {
				$error  = __( 'Only pdf, doc or docx are allowed', 'ksps-resume-form' );
			}

			$upload_file = wp_upload_bits( $file['name'] , null, file_get_contents($file['tmp_name']) );

			if (!$upload_file['error']) {
				$cv = $upload_file['url'];
			}

		}

		if ( empty( $cv ) ) {
			$error = __( 'Please upload your cv.', 'ksps-resume' );
		}

		if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $phone ) || empty( $post_name ) ) {
			$error = __( 'Fill up all required fields', 'ksps-resume' );
		}

		if ( ! empty( $error ) ) {
			wp_send_json_error( [
				'message' => $error
			] );
		}

		$args = [
			'first_name'        => $first_name,
			'last_name'         => $last_name,
			'email'             => $email,
			'present_address'   => $present_address,
			'phone'             => $phone,
			'post_name'         => $post_name,
			'cv'                => $cv
		];

		if ( $id ) {
			$args['id'] = $id;
		}

		$insert_id = ksps_resume_insert_resume( $args );

		if ( $insert_id ) {
			wp_send_json_success([
				'message' => __( 'Application Submitted successfully!', 'ksps-resume-form' )
			]);
		} else {
			wp_send_json_error([
				'message' => __( 'Theres an error submitting application..', 'ksps-resume-form' )
			]);
		}


	}

}