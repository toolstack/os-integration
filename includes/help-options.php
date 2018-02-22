<?php
function osintegration_create_help_screen() {
	if( !is_admin() ) {
		wp_die (__( 'Access denied!', 'os-integration' ) );
	}

	$help_screen = WP_Screen::get();

	$help_screen->add_help_tab(
		array(
			'title'    => 	__( 'General', 'os-integration' ),
			'id'       => 	'general_tab',
			'content'  => 	'<p>' . __( '<b>Square Image</b>: This is a square image file to use to generate the various display items.  This image MUST be GREATER than 450x450 px, if it is not then some items will not display correctly or at all.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Wide Image</b>: This is a wide image file to use to generate display items in a wide screen format.  If this is left blank the square image above will be used.  This image MUST be GREATER than 450x218 px, if it is not then some items will not display correctly or at all.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Background Color</b>: This is the background color to use in icons and live tiles.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>SIte Title</b>: This will be the text displayed on your Live Title or iOS app icon, by default this will be your blog title.', 'os-integration' ) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__( 'Fav Icon', 'os-integration' ),
			'id'       => 	'fav_icon_tab',
			'content'  => 	'<p>' . __( '<b>Enable Fav Icon</b>: Enable the generate and usage of the Fav Icon.  This will both generate an .ico file as well as png files with appropriate <link> references in the html header.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Fav Icon Path</b>: This is the location to store the .ico file, you must have write access to this location.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Include 64px Image</b>: When generating the .ico file, this will include a 64x64px image as well.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Include 96px Image</b>: When generating the .ico file, this will include a 96x96px image as well.', 'os-integration' ) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__( 'PWA', 'os-integration' ),
			'id'       => 	'pwa_tab',
			'content'  => 	'<p>' . __( '<b>Enable Progressive Web App</b>: Enable the PWA supprt by generating a manifest.json file and <link> reference in the html header.  Note, a site must support https for PWA support to function.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Short Name</b>: This is a short nickname for the site.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Name</b>: The full site name.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Descrition</b>: The site description.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Landing URL</b>: The url to open when loading the PWA.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Display Mode</b>: Setting to; "Browser" will open the PWA in a full browser window, "Fullscreen" will open the PWA in a full screen browser window, "Minimal UI" will open the PWA in an application window with limited browser controls, "Standalone" will open the PWA in an application window with no controls.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Orientation</b>: Start the PWA in a given orientation.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Theme Color</b>: Theme color for the PWA.', 'os-integration' ) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__( 'Windows', 'os-integration' ),
			'id'       => 	'windows_tab',
			'content'  => 	'<p>' . __( '<b>Enable Live Tile</b>: This will enable Windows Live Tile support.  Multiple sizes and Live Tile updates are available via <a href="http://buildmypinnedsite.com" target=_blank>buildmypinnedsite.com</a>.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>RSS Feed URL</b>: The RSS feed URL to pass to buildmypinnedsite.com.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Update Interval</b>: This is how often the Windows Live Tile will update it\'s data.  If you update your site often, set this lower.  If you update your site less often, set it higher.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Local XML File</b>: By default, buildmypinnedsite.com is used to proxy your XML updates for Live Tiles.  If you wish to use your own site to host the updates you can do so.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Enabled Local XML</b>: Enable the local XML feed support.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Include featured image</b>: Include the featured image from the posts in the XML feed.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Search body for featured image</b>: If your theme doesn\'t support a featured image or you don\'t use them, OS Integration can search the body of your post for the first image and use it instead.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Use square image if no image found</b>: If no image is found, this option will instead use your square image for the post.  Note if this is not enabled and featured images are enabled, a default WordPress logo will appear.', 'os-integration' ) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__( 'iOS', 'os-integration' ),
			'id'       => 	'ios_tab',
			'content'  => 	'<p>' . __( '<b>Enable iOS</b>: This will enable iOS Icon support.  Both standard and HD icons are supported.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Enable Web App Support</b>: This will enable Web App suppport on iOS for your site including load screens.  Note that Web App support is limited to the first page you load, when a user selects a link that takes them to another page it will open in Safari and leave the Web App.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Enable Link Override</b>: This will enable a JavaScript function that will keep the user in the Web App when they click on a link to another page on your site.  If the user clicks on a link to an external site it will take them to Safari.  NOTE:  This code can have a performance impact on your site responding to clicks and some advanced controls may not work with it.  You should test this option before deploying it to a production site.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Use wide image for web app loading screen</b>: By default the square image is used on the loading screen for the Web App, this option will instead use the wide image.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Web App Logo Location</b>: The location to place the image on the loading screen.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Web App Status Bar Style</b>: the iOS status bar can be set to be the default, black or transparent.', 'os-integration' ) . '</p>'
			,
			'callback' => 	false
		)
	);

		$help_screen->add_help_tab(
		array(
			'title'    => 	__( 'Related Apps', 'os-integration' ),
			'id'       => 	'related_apps_tab',
			'content'  => 	'<p>' . __( 'The releated apps section allows you to define application links to various stores where users can download them.  This is used both in the Progressive Web App manifest as well as the front end widget.', 'os-integration' ) . '</p>' .
						 	'<p>' . __( '<b>App URL</b>: The url to the application for your site in the selected app store, this should include any id parameter required.  Leave blank to not use the selected app store.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>App ID</b>: The unique ID for your app in the selected app store.', 'os-integration' ) . '</p>'
			,
			'callback' => 	false
		)
	);

		$help_screen->add_help_tab(
		array(
			'title'    => 	__( 'Advanced', 'os-integration' ),
			'id'       => 	'advanced_tab',
			'content'  => 	'<p>' . __( '<b>Allow WordPress Site Icon</b>: OS Integration will override WordPress\'s Site Icon settings and strip the meta information from the page headers. If you wish to use WordPress\'s Site Icons, you can override this behaviour by checking this option.  Note this option will only appear if you have WordPress 4.3 or later installed.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Force rebuild</b>: By default, images and other icons are only regenerated when new images are selected, if you have changed the background color or other options and want to regenerate the images, select this option and save the settings.', 'os-integration' ) . '</p>' .
							'<p>' . __( '<b>Override individual image files to be used</b>: All images are auto generated by default, but if you want to select your own custom images to use instead you can enter a URL under each one here.', 'os-integration' ) . '</p>'
			,
			'callback' => 	false
		)
	);
}