<?php
/**
 * Add a new column to post and page admin screens, displaying the number of views.
 *
 * @since 2.0.0
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
	 * @since 2.0.0
	 *
	 * @param array $columns - array of columns in wp-admin.
	 */
	public static function add_view_count_column( $columns ) {
		/*
		 * Place our colunm right after Jetpack's own Stats column.
		 * Jetpack's Stats column and our "views" column are related, after all.
		 * They need to be close to each other.
		 * Let's reorganize the array a bit.
		 */
		if ( isset( $columns['stats'] ) ) {
			$stats = $columns['stats'];
			unset( $columns['stats'] );
			$columns['stats'] = $stats;
		}
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
		require_once JPPOSTVIEWS__PLUGIN_DIR . 'functions.jp-post-views.php';
		$views = jp_post_views_get_view( $post_id );

		if ( 'views' === $column_name ) {
			$view_count = ! empty( $views )
				? number_format_i18n( $views )
				: '—';

			printf(
				'<span class="view-count">%s</span>',
				esc_html( $view_count )
			);
		}
	}
}
