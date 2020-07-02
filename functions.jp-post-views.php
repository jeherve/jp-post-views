<?php
/**
 * Functions used to retrieve, store, and display Post Views on your site.
 *
 * @package Post Views for Jetpack
 * @since 1.0.0
 */

/**
 * Retrieve Post Views for a post, using the WordPress.com Stats API.
 *
 * @since 1.0.0
 *
 * @param string $post_id Post ID.
 *
 * @return array $view Post View.
 */
function jp_post_views_get_view( $post_id ) {
	// Start with an empty array.
	$view = array();

	// Return early if we use a too old version of Jetpack.
	if ( ! function_exists( 'stats_get_from_restapi' ) ) {
		return;
	}

	// Build our sub-endpoint to get stats for a specific post.
	$endpoint = sprintf(
		'post/%d',
		$post_id
	);

	// Get the data.
	$stats = stats_get_from_restapi( array( 'fields' => 'views' ), $endpoint );
	// Process that data.
	if (
		isset( $stats )
		&& ! empty( $stats )
		&& isset( $stats->views )
	) {
		$view = array(
			'total'     => $stats->views,
			'cached_at' => isset( $stats->cached_at ) ? $stats->cached_at : '',
		);
		update_post_meta( $post_id, '_post_views', $view );
	}

	return $view;
}

/**
 * Retrieve all time stats for your site.
 *
 * @since 1.0.0
 *
 * @return string $views All time views for that site.
 */
function jp_post_views_get_all_views() {
	// Start with an empty array.
	$views = array();

	// Get the data.
	$stats = stats_get_from_restapi( array( 'fields' => 'stats' ) );
	if (
		isset( $stats )
		&& ! empty( $stats )
		&& isset( $stats->stats )
	) {
		$views = array(
			'total'     => $stats->stats->views,
			'cached_at' => isset( $stats->cached_at ) ? $stats->cached_at : '',
		);
	}

	return $views;
}

/**
 * Create a shortcode to display a post view inside a post.
 * Shortcode format is [jp_post_view]
 *
 * @since 1.0.0
 *
 * @return string $view Total number of views for that post.
 */
function jp_post_views_display() {
	// Get the post ID.
	$post_id = get_the_ID();

	if ( ! isset( $post_id ) || empty( $post_id ) ) {
		return;
	}

	// Get the number of views for that post.
	$views = jp_post_views_get_view( $post_id );

	if ( isset( $views ) && ! empty( $views ) ) {
		$view = sprintf(
			esc_html(
				_n(
					'%s view',
					'%s views',
					$views['total'],
					'post-views-for-jetpack'
				)
			),
			number_format_i18n( $views['total'] )
		);
	} else {
		$view = esc_html__( 'no views', 'post-views-for-jetpack' );
	}

	/**
	 * Filter the output of the shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $view    Phrase outputting the number of views.
	 * @param array  $views   Number of views.
	 * @param string $post_id Post ID.
	 */
	return apply_filters( 'jp_post_views_output', $view, $views, $post_id );
}
