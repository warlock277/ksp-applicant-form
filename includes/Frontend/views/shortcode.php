<div class="ksps-resume-submit-form" id="ksps-resume-submit-form">

	<form action="" method="post">

        <div class="form-message-success">Success Message</div>
        <div class="form-message-error">Error Message</div>

        </p>
		<div class="form-row">
			<label for="first_name"><?php _e( 'First Name', 'ksps-resume' ); ?></label>
			<input type="text" id="first_name" name="first_name" value="" required>
		</div>

		<div class="form-row">
			<label for="last_name"><?php _e( 'Last Name', 'ksps-resume' ); ?></label>
			<input type="text" id="last_name" name="last_name" value="" required>
		</div>

        <div class="form-row">
            <label for="present_address"><?php _e( 'Present Address', 'ksps-resume' ); ?></label>
            <input type="text" id="present_address" name="present_address" value="" required>
        </div>

        <div class="form-row">
            <label for="email"><?php _e( 'E-Mail Address', 'ksps-resume' ); ?></label>
            <input type="email" id="email" name="email" value="" >
        </div>

        <div class="form-row">
			<label for="phone"><?php _e( 'Mobile No', 'ksps-resume' ); ?></label>
			<input type="text" id="phone" name="phone" value="" required>
		</div>
        <div class="form-row">
            <label for="post_name"><?php _e( 'Post Name', 'ksps-resume' ); ?></label>
            <input type="text" id="post_name" name="post_name" value="" required>
        </div>

        <div class="form-row">
            <label for="cv"><?php _e( 'Your CV', 'ksps-resume' ); ?></label>
            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required />
        </div>

		<div class="form-row">
			<?php wp_nonce_field( 'ksps-resume-form' ); ?>
			<input type="hidden" name="action" value="ksps_resume_form_submit">
			<input type="submit" name="send_resume" value="<?php esc_attr_e( 'Send Resume', 'ksps-resume' ); ?>">
		</div>

	</form>
</div>