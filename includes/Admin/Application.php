<?php

namespace Ksps\Resume\Admin;

use Ksps\Resume\Traits\Form_Error;

/**
 * Resume Handler class
 */
class Application {

    use Form_Error;

    /**
     * Plugin page handler
     *
     * @return void
     */
    public function plugin_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {
            case 'new':
                $template = __DIR__ . '/views/resume-new.php';
                break;

            case 'edit':
                $resume  = ksps_resume_get_resume( $id );
                $template = __DIR__ . '/views/resume-edit.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/resume-view.php';
                break;

            default:
                $template = __DIR__ . '/views/resume-list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * Handle the form
     *
     * @return void
     */
    public function form_handler() {

        if ( ! isset( $_POST['submit_resume'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-resume' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id                 = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
        $first_name         = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
	    $last_name          = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
        $email              = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	    $present_address    = isset( $_POST['present_address'] ) ? sanitize_text_field( $_POST['present_address'] ) : '';
        $phone              = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	    $post_name          = isset( $_POST['post_name'] ) ? sanitize_text_field( $_POST['post_name'] ) : '';

        if ( empty( $first_name ) ) {
            $this->errors['first_name'] = __( 'Please provide a first name', 'ksps-resume' );
        }

	    if ( empty( $last_name ) ) {
		    $this->errors['last_name'] = __( 'Please provide a last name', 'ksps-resume' );
	    }

	    if ( empty( $email ) ) {
		    $this->errors['email'] = __( 'Please provide an email', 'ksps-resume' );
	    }

        if ( empty( $phone ) ) {
            $this->errors['phone'] = __( 'Please provide a phone number.', 'ksps-resume' );
        }

	    if ( empty( $post_name ) ) {
		    $this->errors['post_name'] = __( 'Please provide a post name.', 'ksps-resume' );
	    }

	    if ( empty( $present_address ) ) {
		    $this->errors['present_address'] = __( 'Please provide present address.', 'ksps-resume' );
	    }




        if ( ! empty( $this->errors ) ) {
            return;
        }

        $args = [
            'first_name'        => $first_name,
            'last_name'         => $last_name,
            'email'             => $email,
            'present_address'   => $present_address,
            'phone'             => $phone,
            'post_name'         => $post_name,
        ];

        if ( $id ) {
            $args['id'] = $id;
        }

        $insert_id = ksps_resume_insert_resume( $args );

        if ( is_wp_error( $insert_id ) ) {
            wp_die( $insert_id->get_error_message() );
        }

        if ( $id ) {
            $redirected_to = admin_url( 'admin.php?page=ksps-resume&action=edit&resume-updated=true&id=' . $id );
        } else {
            $redirected_to = admin_url( 'admin.php?page=ksps-resume&inserted=true' );
        }

        wp_redirect( $redirected_to );
        exit;
    }

    public function delete_application() {

        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'ksps-resume-admin-nonce' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

        ksps_resume_delete_resume( $id );
	    wp_send_json_success();
    }
}