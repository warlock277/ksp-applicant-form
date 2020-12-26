<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'Resumes', 'ksps-resume' ); ?></h1>

	<a href="<?php echo admin_url( 'admin.php?page=ksps-resume&action=new' ); ?>" class="page-title-action"><?php _e( 'Add New', 'ksps-resume' ); ?></a>

	<?php if ( isset( $_GET['inserted'] ) ) { ?>
		<div class="notice notice-success">
			<p><?php _e( 'Resume has been added successfully!', 'ksps-resume' ); ?></p>
		</div>
	<?php } ?>

	<?php if ( isset( $_GET['resume-deleted'] ) && $_GET['resume-deleted'] == 'true' ) { ?>
		<div class="notice notice-success">
			<p><?php _e( 'Resume has been deleted successfully!', 'ksps-resume' ); ?></p>
		</div>
	<?php } ?>

	<form action="" method="post">
		<?php
		$table = new Ksps\Resume\Admin\Resume_List();
		$table->prepare_items();
		$table->search_box( 'search', 'search_id' );
		$table->display();
		?>
	</form>
</div>