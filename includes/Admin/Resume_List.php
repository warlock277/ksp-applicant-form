<?php

namespace Ksps\Resume\Admin;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List Table Class
 */
class Resume_List extends \WP_List_Table {

	function __construct() {
		parent::__construct( [
			'singular' => 'resume',
			'plural'   => 'resumes',
			'ajax'     => false
		] );
	}

	/**
	 * Message to show if no resume found
	 *
	 * @return void
	 */
	function no_items() {
		_e( 'No resume found', 'ksps-resume' );
	}

	/**
	 * Get the column names
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'cb'                => '<input type="checkbox" />',
			'first_name'        => __( 'First Name', 'ksps-resume' ),
			'last_name'         => __( 'Last Name', 'ksps-resume' ),
			'email'             => __( 'Email', 'ksps-resume' ),
			'present_address'   => __( 'Present Address', 'ksps-resume' ),
			'phone'             => __( 'Phone', 'ksps-resume' ),
			'post_name'         => __( 'Post Name', 'ksps-resume' ),
			'cv'                => __( 'CV', 'ksps-resume' ),
			'created_at'        => __( 'Submission date', 'ksps-resume' ),
		];
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		$sortable_columns = [
			'name'       => [ 'name', true ],
			'created_at' => [ 'created_at', true ],
		];

		return $sortable_columns;
	}

	/**
	 * Set the bulk actions
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		$actions = array(
			'trash'  => __( 'Move to Trash', 'ksps-resume' ),
		);

		return $actions;
	}

	/**
	 * Default column values
	 *
	 * @param  object $item
	 * @param  string $column_name
	 *
	 * @return string
	 */
	protected function column_default( $item, $column_name ) {

		switch ( $column_name ) {

			case 'created_at':
				return wp_date( get_option( 'date_format' ), strtotime( $item->created_at ) );

			case 'cv':
				return sprintf('<a href="%s" target="_blank">View</a>', $item->cv);

			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}

	/**
	 * Render the "first_name" column
	 *
	 * @param  object $item
	 *
	 * @return string
	 */
	public function column_first_name( $item ) {
		$actions = [];

		$actions['edit']   = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=ksps-resume&action=edit&id=' . $item->id ), $item->id, __( 'Edit', 'ksps-resume' ), __( 'Edit', 'ksps-resume' ) );
		$actions['delete'] = sprintf( '<a href="#" class="submitdelete" data-id="%s">%s</a>', $item->id, __( 'Delete', 'ksps-resume' ) );

		return sprintf(
			'<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=ksps-resume&action=view&id' . $item->id ), $item->first_name, $this->row_actions( $actions )
		);
	}

	/**
	 * Render the "cb" column
	 *
	 * @param  object $item
	 *
	 * @return string
	 */
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="resume_id[]" value="%d" />', $item->id
		);
	}

	/**
	 * Prepare the address items
	 *
	 * @return void
	 */
	public function prepare_items() {
		$column   = $this->get_columns();
		$hidden   = [];
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $column, $hidden, $sortable ];

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;

		$args = [
			'number' => $per_page,
			'offset' => $offset,
		];

		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
			$args['orderby'] = $_REQUEST['orderby'];
			$args['order']   = $_REQUEST['order'] ;
		}

		if ( isset( $_REQUEST['s'] ) ) {
			$args['s']   = $_REQUEST['s'] ;
		}

		$this->items = ksps_resume_get_resumes( $args );

		$this->set_pagination_args( [
			'total_items' => ksps_resume_resumes_count(),
			'per_page'    => $per_page
		] );
	}

	/**
	 * Displays the search box.
	 *
	 * @since 3.1.0
	 *
	 * @param string $text     The 'submit' button label.
	 * @param string $input_id ID attribute value for the search input field.
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
			return;

		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) )
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		if ( ! empty( $_REQUEST['order'] ) )
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		if ( ! empty( $_REQUEST['post_mime_type'] ) )
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
		if ( ! empty( $_REQUEST['detached'] ) )
			echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
			<input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( $text, 'button', '', false, array('id' => 'search-submit') ); ?>
		</p>
		<?php
	}
}