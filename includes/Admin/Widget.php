<?php

namespace Ksps\Resume\Admin;

/**
 * The Menu handler class
 */
class Widget {

    /**
     * Initialize the class
     */
    function __construct( ) {
	    add_action( 'wp_dashboard_setup', [ $this, 'ksps_resume_add_dashboard_widgets' ] );
    }

	/**
	 * Add a new applicant_submission dashboard widget.
	 */
	public function ksps_resume_add_dashboard_widgets() {
		wp_add_dashboard_widget( 'dashboard_widget', 'Latest Applicant Submissions ', [ $this, 'ksps_resume_dashboard_widget_function' ] );
	}


	/**
	 * Output the applicant_submission dashboard widget
	 */
	public function ksps_resume_dashboard_widget_function( $post, $callback_args ) {

		wp_enqueue_style( 'ksps-resume-widget-style' );

		$last_resumes = ksps_resume_get_resumes([
			'number'  => 5,
			'offset'  => 0,
			'orderby' => 'created_at',
			'order'   => 'DESC'
		]);

		if( count( $last_resumes ) > 0 ) {
		?>
		<ul class="ksps-resume-dashboard-widget">
		<?php
		foreach ( $last_resumes as $resume ) { ?>
			<li>
				<span><?php echo wp_date( get_option( 'date_format' ), strtotime( $resume->created_at ) ) ?></span>
				<span><?php echo $resume->post_name; ?></span>
				<span><a href="<?php echo $resume->cv; ?>" target="_blank"><?php echo $resume->first_name.' '.$resume->last_name; ?></a></span>
			</li>
			<?php
		}
		?>
		</ul>
		<?php
		}
		else {
			echo '<span>No Application Submitted yet</span>';
		}

	}
}
