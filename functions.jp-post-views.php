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
 * Create a shortcode to display a post view inside a post.
 * Shortcode format is [jp_post_view]
 *
 * @since 1.0.0
 *
 * @return string $view Total number of views for that post.
 */
function jp_post_views_shortcode() {
	// Get the post ID.
	$post_id = get_the_ID();

	// Get the number of views for that post.
	$views = jp_post_views_get_view( $post_id );

	if ( isset( $views ) && ! empty( $views ) ) {
		$view = sprintf( esc_html(
			_n(
				'%d view',
				'%d views',
				$views['total'],
				'jp-post-views'
			)
		), $views['total'] );
	} else {
		$view = esc_html__( 'no views', 'jp-post-views' );
	}

	return $view;
}
