<?php
/**
 * Functions used to retrieve, store, and display Post Views on your site.
 *
 * @package Post Views for Jetpack
 * @since 1.0.0
 */

use Automattic\Jetpack\Stats\WPCOM_Stats;

/**
 * Retrieve Post Views for a post, using the WordPress.com Stats API.
 *
 * @since 1.0.0
 *
 * @param string $post_id Post ID.
 *
 * @return int $view Post View.
 */
function jp_post_views_get_view( $post_id ) {
	/**
	 * Allow setting up your own duration for the cache.
	 * 
	 * @deprecated 2.0.0 This filter is no longer necessary, it is done within the Jetpack plugin now.
	 *
	 * @since 1.5.0
	 *
	 * @param int $duration The duration of the cache in seconds. Default to an hour.
	 */
	$cache_duration = (int) apply_filters_deprecated(
		'jp_post_views_cache_duration',
		array( HOUR_IN_SECONDS ),
		'2.0.0',
		'',
		esc_html__( 'Caching this data is no longer necessary, it is done within the Jetpack plugin now.', 'post-views-for-jetpack' )
	);

	// Get the data for a specific post.
	$stats = jp_post_views_convert_stats_array_to_object(
		( new WPCOM_Stats() )->get_post_views( (int) $post_id, array( 'fields' => 'views' ), true )
	);

	return $stats->views ?? 0;
}

/**
 * Retrieve all time stats for your site.
 *
 * @since 1.0.0
 *
 * @return int $views All time views for that site.
 */
function jp_post_views_get_all_views() {
	// Get the data.
	$stats = jp_post_views_convert_stats_array_to_object(
		( new WPCOM_Stats() )->get_stats( array( 'fields' => 'stats' ) )
	);

	return $stats->stats->views ?? 0;
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
	if ( empty( $post_id ) ) {
		return;
	}

	// Get the number of views for that post.
	$views = jp_post_views_get_view( $post_id );
	if ( ! empty( $views ) ) {
		$view = sprintf(
			esc_html(
				_n(
					'%s view',
					'%s views',
					$views,
					'post-views-for-jetpack'
				)
			),
			number_format_i18n( $views )
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

/**
 * Convert stats array to object after sanity checking the array is valid.
 * Lifted from Jetpack.
 * @see https://github.com/Automattic/jetpack/blob/8a79f5e319d5da58de1b8f0bda863957b938bf21/projects/plugins/jetpack/modules/stats.php#L1522-L1538
 *
 * @param  array $stats_array The stats array.
 * @return WP_Error|Object|null
 */
function jp_post_views_convert_stats_array_to_object( $stats_array ) {

	if ( is_wp_error( $stats_array ) ) {
		return $stats_array;
	}
	$encoded_array = wp_json_encode( $stats_array );
	if ( ! $encoded_array ) {
		return new WP_Error( 'stats_encoding_error', 'Failed to encode stats array' );
	}
	return json_decode( $encoded_array );
}