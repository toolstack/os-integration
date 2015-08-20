<?php
	function os_integraton_widget() {
		register_widget( 'OSIntegration_Widget' );
	}

	class OSIntegration_Widget extends WP_Widget {

		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			parent::__construct(
				'OSIntegration_Widget', // Base ID
				__('OS Integration', 'os_integraton'), // Name
				array( 'description' => __('Show the OS Integration notice to users.', 'os_integraton') ) // Args
				);
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			extract( $args );
			
			$options = get_option( OSINTOPTIONNAME );

			// If at least one of the options is enabled, output the widget.
			if( ( array_key_exists( 'enablefavicon', $options ) && $options['enablefavicon'] ) || 
				( array_key_exists( 'enablelivetile', $options ) && $options['enablelivetile'] ) || 
				( array_key_exists( 'enableios', $options ) && $options['enableios'] ) || 
				( array_key_exists( 'enablewebapp', $options ) && $options['enablewebapp'] ) )
				{
				$bookmark = "";
				$pin = "";
				$or = "";
				
				if( array_key_exists( 'enablefavicon', $options ) && $options['enablefavicon'] ) { $bookmark = "Bookmark"; }
				if( array_key_exists( 'enablelivetile', $options ) && $options['enablelivetile'] || $options['enableios'] || $options['enablewebapp'] ) { $pin = "Pin"; }
				
				if( $bookmark != "" && $pin != "" ) { $or = " or "; }
				
				echo $before_widget;
				echo $before_title . $bookmark . $or . $pin . " us!" . $after_title;
							
				echo "<p>" . get_bloginfo('name') . "  supports many popular operating systems!</p>";
				echo "<p>Supported platforms include:</p>";
				echo "<ul>";
				
				if( array_key_exists( 'enablefavicon', $options ) && $options['enablefavicon'] ) { echo "<li>FavIcons for desktop and mobile browsers</li>"; }
				if( array_key_exists( 'enablelivetile', $options ) && $options['enablelivetile'] ) { echo "<li>Windows 8 and Windows Phone 8.1 Live Tiles</li>"; }
				if( array_key_exists( 'enableios', $options ) && $options['enableios'] ) { echo "<li>iOS Home Screen Icons</li>"; }
				if( array_key_exists( 'enablewebapp', $options ) && $options['enablewebapp'] ) { echo "<li>iOS WebApp</li>"; }
				
				echo "</ul>";
				
				echo $after_widget;
			}
		}
	}	

	add_action( 'widgets_init', 'os_integraton_widget' );
	
?>