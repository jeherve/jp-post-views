<?php
/**
 * Add a new column to post and page admin screens, displaying the number of views.
 *
 * @since 1.6.0
 *
 * @package Post_Views_For_Jetpack
 */

/**
 * Jeherve_Post_Views_Admin_Cols class.
 */
class Jeherve_Post_Views_Admin_Cols {
	/**
	 * Add a new column to post and page admin screens, displaying the number of views.
	 *
	 * @since 1.6.0
	 *
	 * @param array $columns - array of columns in wp-admin.
	 */
	public static function add_view_count_column( $columns ) {
		$stats = $columns['stats'];
		unset( $columns['stats'] );

		$columns['stats'] = $stats;
		$columns['views'] = esc_html__( 'Views', 'post-views-for-jetpack' );

		return $columns;
	}

	/**
	 * Add "Likes" column data to the post edit table in wp-admin.
	 *
	 * @param string $column_name - name of the column.
	 * @param int    $post_id - the post id.
	 */
	public static function view_count_edit_column( $column_name, $post_id ) {
		$views = jp_post_views_get_view( $post_id );

		if ( 'views' === $column_name ) {
			$view_count = ! empty( $views ) && isset( $views['total'] )
				? $views['total']
				: 0;

			printf(
				'<span class="view-count">%s</span>',
				number_format_i18n( (int) $view_count )
			);
		}
	}
}
