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
			__( 'OS Integration', 'os-integraton' ), // Name
			array( 'description' => __( 'Show the OS Integration notice to users.', 'os-integraton' ) ) // Args
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
			( array_key_exists( 'enablewebapp', $options ) && $options['enablewebapp'] ) ) {
			$title = __( 'Bookmark us!', 'os-integration' );
			$pin      = __( 'Bookmark or Pin us!', 'os-integration' );
			
			
			if( array_key_exists( 'enablelivetile', $options ) && $options['enablelivetile'] || $options['enableios'] || $options['enablewebapp'] || $options['enablepwa'] ) { 
				$title = __( 'Bookmark or Pin us!', 'os-integration' );
			}
			
			echo $before_widget;
			echo $before_title . $title . $after_title;
						
			echo '<p>' . sprintf( __( '%s supports many popular operating systems!', 'os-integraton' ), get_bloginfo( 'name' ) ) . '</p>';
			echo '<p>' . __( 'Supported platforms include:', 'os-integration' ) . '</p>';
			echo '<ul>';
			
			if( array_key_exists( 'enablefavicon', $options ) && $options['enablefavicon'] ) { echo '<li>' . __( 'FavIcons for desktop and mobile browsers', 'os-integration' ) . '</li>'; }
			if( array_key_exists( 'enablepwa', $options ) && $options['enablefavicon'] ) { echo '<li>' . __( 'Progressive Web Apps for desktop and mobile browsers', 'os-integration' ) . '</li>'; }
			if( array_key_exists( 'enablelivetile', $options ) && $options['enablelivetile'] ) { echo '<li>' . __( 'Windows 8 and Windows Phone 8.1 Live Tiles', 'os-integration' ) . '</li>'; }
			if( array_key_exists( 'enableios', $options ) && $options['enableios'] ) { echo '<li>' . __( 'iOS Home Screen Icons', 'os-integration' ) . '</li>'; }
			if( array_key_exists( 'enablewebapp', $options ) && $options['enablewebapp'] ) { echo '<li>' . __( 'iOS WebApp', 'os-integration' ) . '</li>'; }
			
			echo '</ul>';
			
			echo $after_widget;
		}
	}
}	

add_action( 'widgets_init', 'os_integraton_widget' );
	
?>