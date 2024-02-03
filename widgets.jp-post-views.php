<?php
/**
 * Functions used to build the different widgets available in the plugin.
 *
 * @package Post Views for Jetpack
 * @since 1.0.0
 */

/**
 * All Stats widget.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Jp_Post_Views_All_Widget' ) ) {

	/**
	 * Register All Stats widget.
	 *
	 * @since 1.0.0
	 */
	function jp_post_views_all_widget_init() {
		register_widget( 'Jp_Post_Views_All_Widget' );
	}
	add_action( 'widgets_init', 'jp_post_views_all_widget_init' );

	/**
	 * Makes a custom Widget for displaying All Time stats on your site.
	 *
	 * @since 1.0.0
	 */
	class Jp_Post_Views_All_Widget extends WP_Widget {

		/**
		 * Constructor
		 */
		function __construct() {
			$widget_ops = array(
				'classname'   => 'jp_post_views_all',
				'description' => __( 'Display All Time Stats on your site.', 'post-views-for-jetpack' ),
			);
			parent::__construct(
				'jp_post_views_all',
				esc_html__( 'All Time Site Stats', 'post-views-for-jetpack' ),
				$widget_ops
			);
			$this->alt_option_name = 'jp_post_views_all';
		}


		/**
		 * Return an associative array of default values.
		 * These values are used in new widgets.
		 *
		 * @since 1.0.0
		 *
		 * @return array Array of default values for the Widget's options.
		 */
		public function defaults() {
			return array(
				'title'   => __( 'All Time Stats', 'post-views-for-jetpack' ),
			);
		}

		/**
		 * Outputs the HTML for this widget.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     An array of standard parameters for widgets in this theme.
		 * @param array $instance An array of settings for this widget instance.
		 **/
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults() );

			echo $args['before_widget'];

			if ( '' != $instance['title'] ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			}

			// Get the Site Stats.
			$views = jp_post_views_get_all_views();

			if ( ! empty( $views ) ) {
				$stats_output = sprintf(
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
				$stats_output = esc_html__( 'No views', 'post-views-for-jetpack' );
			}

			/**
			 * Filter the display of the All Time Stats in the widget.
			 *
			 * @since 1.0.0
			 *
			 * @param string $stats_output Text displayed to show all time stats in the widget.
			 * @param int    $views        Number of Total views on the site.
			 */
			echo apply_filters( 'jp_post_views_all_time_stats_ouput', $stats_output, $views );

			echo $args['after_widget'];
		}


		/**
		 * Deals with the settings when they are saved by the admin. Here is
		 * where any validation should be dealt with.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New configuration values.
		 * @param array $old_instance Old configuration values.
		 *
		 * @return array
		 */
		function update( $new_instance, $old_instance ) {
			$instance          = array();
			$instance['title'] = wp_kses( $new_instance['title'], array() );
			return $instance;
		}


		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Instance configuration.
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults() );

			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'post-views-for-jetpack' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<?php
		}

	}

}
