<div class="wrap">
	<h1><?php _e( 'New Resume', 'ksps-resume' ); ?></h1>

	<form action="" method="post">
		<table class="form-table">
			<tbody>
			<tr class="row<?php echo $this->has_error( 'first_name' ) ? ' form-invalid' : '' ;?>">
				<th scope="row">
					<label for="name"><?php _e( 'First Name', 'ksps-resume' ); ?></label>
				</th>
				<td>
					<input type="text" name="first_name" id="first_name" class="regular-text" value="">

					<?php if ( $this->has_error( 'first_name' ) ) { ?>
						<p class="description error"><?php echo $this->get_error( 'first_name' ); ?></p>
					<?php } ?>
				</td>
			</tr>
			<tr class="row<?php echo $this->has_error( 'last_name' ) ? ' form-invalid' : '' ;?>">
				<th scope="row">
					<label for="name"><?php _e( 'Last Name', 'ksps-resume' ); ?></label>
				</th>
				<td>
					<input type="text" name="last_name" id="last_name" class="regular-text" value="">

					<?php if ( $this->has_error( 'last_name' ) ) { ?>
						<p class="description error"><?php echo $this->get_error( 'last_name' ); ?></p>
					<?php } ?>
				</td>
			</tr>
			<tr class="row<?php echo $this->has_error( 'email' ) ? ' form-invalid' : '' ;?>">
				<th scope="row">
					<label for="name"><?php _e( 'Email Address', 'ksps-resume' ); ?></label>
				</th>
				<td>
					<input type="email" name="email" id="email" class="regular-text" value="">

					<?php if ( $this->has_error( 'email' ) ) { ?>
						<p class="description error"><?php echo $this->get_error( 'email' ); ?></p>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="present_address"><?php _e( 'Present Address', 'ksps-resume' ); ?></label>
				</th>
				<td>
					<input type="text" name="present_address" id="present_address" class="regular-text" value="">

					<?php if ( $this->has_error( 'present_address' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'present_address' ); ?></p>
					<?php } ?>
				</td>
			</tr>
			<tr class="row<?php echo $this->has_error( 'phone' ) ? ' form-invalid' : '' ;?>">
				<th scope="row">
					<label for="phone"><?php _e( 'Phone', 'ksps-resume' ); ?></label>
				</th>
				<td>
					<input type="text" name="phone" id="phone" class="regular-text" value="">

					<?php if ( $this->has_error( 'phone' ) ) { ?>
						<p class="description error"><?php echo $this->get_error( 'phone' ); ?></p>
					<?php } ?>
				</td>
			</tr>
            <tr>
                <th scope="row">
                    <label for="post_name"><?php _e( 'Post Name', 'ksps-resume' ); ?></label>
                </th>
                <td>
                    <input type="text" name="post_name" id="post_name" class="regular-text" value="">

	                <?php if ( $this->has_error( 'post_name' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'post_name' ); ?></p>
	                <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cv"><?php _e( 'CV', 'ksps-resume' ); ?></label>
                </th>
                <td>
                    <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" />

	                <?php if ( $this->has_error( 'cv' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'cv' ); ?></p>
	                <?php } ?>
                </td>
            </tr>
			</tbody>
		</table>

		<?php wp_nonce_field( 'new-resume' ); ?>
		<?php submit_button( __( 'Add Resume', 'ksps-resume' ), 'primary', 'submit_resume' ); ?>
	</form>
</div>