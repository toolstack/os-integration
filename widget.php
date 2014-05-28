<?php
	function os_integraton_widget() {
	
		wp_register_sidebar_widget('os-integraton', 'OS Integration', 'os_integraton_show_widget', array(
			'description'	=>	__('Show the OS Integration notice to users.', 'os_integraton')));
	}

	add_action("plugins_loaded", "os_integraton_widget");

	function os_integraton_show_widget($args) {
	
		extract( $args );
		
		$options = get_option( ISINTOPTIONNAME );

		echo $before_widget;
		echo $before_title . "Bookmark or Pin us!" . $after_title;
		
		
		echo "<p>" . get_bloginfo('name') . "  supports many popular OS's for bookmarking and pinning!</p>";
		echo "<p>Supported platforms include:</p>";
		echo "<ul>";
		
		if( $options['enablefavicon'] ) { echo "<li>FavIcons for desktop and mobile browsers</li>"; }
		if( $options['enablelivetile'] ) { echo "<li>Windows 8 and Windows Phone 8.1 Live Tiles</li>"; }
		if( $options['enableios'] ) { echo "<li>iOS Home Screen Icons</li>"; }
		if( $options['enablewebapp'] ) { echo "<li>iOS WebApp</li>"; }
		
		echo "</ul>";
		
		echo $after_widget;
	}

?>