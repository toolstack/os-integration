<?php
function osintegration_output()
	{
	$options = get_option( OSINTOPTIONNAME );

	// Get our RSS2 feed url.
	if( !isset( $options['rssurl'] ) )
		{
		$options['rssurl'] = get_bloginfo( 'rss2_url' );
		}

	$feed_url = $options['rssurl'];

	if( array_key_exists( 'localxml', $options ) && $options['localxml'] )
		{
		// If we're using our own feed, construct the url for it.
		$feed_url =  site_url() . '/mslivetile';

		// Setup the pooling uri for our own feed.
		$polling_uri  = $feed_url . '&amp;id=1;' .
						$feed_url . '&amp;id=2;' .
						$feed_url . '&amp;id=3;' .
						$feed_url . '&amp;id=4;' .
						$feed_url . '&amp;id=5;';
		}
	else
		{
		// Setup the pooling uri for Microsoft.
		$polling_uri  = 'http://notifications.buildmypinnedsite.com/?feed=' . $feed_url . '&amp;id=1;' .
						'polling-uri2=http://notifications.buildmypinnedsite.com/?feed=' . $feed_url . '&amp;id=2;' .
						'polling-uri3=http://notifications.buildmypinnedsite.com/?feed=' . $feed_url . '&amp;id=3;' .
						'polling-uri4=http://notifications.buildmypinnedsite.com/?feed=' . $feed_url . '&amp;id=4;' .
						'polling-uri5=http://notifications.buildmypinnedsite.com/?feed=' . $feed_url . '&amp;id=5;';
		}

	// Get the polling interval, if not set, default to 6 hours.
	if( osintegration_getoption( 'notification_frequency', $options ) )
		{
		$polling_frequency = osintegration_getoption( 'notification_frequency', $options );
		}
	else
		{
		$polling_frequency = 720;
		}

	// If we're supporting Fav Icons, output the required code now.
	if( osintegration_getoption( 'enablefavicon', $options ) && osintegration_getoption( 'img_square_16', $options ) )
		{
?>

<!-- For PNG Fav Icons -->
<link rel="icon" type="image/png" href="<?php echo osintegration_getoption( 'img_square_196', $options ); ?>" sizes="196x196">
<link rel="icon" type="image/png" href="<?php echo osintegration_getoption( 'img_square_160', $options ); ?>" sizes="160x160">
<link rel="icon" type="image/png" href="<?php echo osintegration_getoption( 'img_square_96', $options ); ?>" sizes="96x96">
<link rel="icon" type="image/png" href="<?php echo osintegration_getoption( 'img_square_32', $options ); ?>" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo osintegration_getoption( 'img_square_16', $options ); ?>" sizes="16x16">

<?php
		}
		// End Fav Icon

	// If we're supporting Progressive Web Apps, output the required code now.
	if( osintegration_getoption( 'enablepwa', $options ) )
		{
		// We need a few variables to use later on, set them up now.
		$upload_dir = wp_upload_dir();
		$base = trailingslashit( $upload_dir['baseurl'] ) . 'os-integration/';

		echo '<!-- For Progressive Web Apps -->' . PHP_EOL;
		echo '<link rel="manifest" href="' . $base . 'manifest.json">' . PHP_EOL . PHP_EOL;
		}

	// If we're supporting Windows 8 live tiles, output the required code now.
	if( osintegration_getoption( 'enablelivetile', $options ) && osintegration_getoption( 'img_square_310', $options ) )
		{
?>
<!-- For pinned live tiles in Windows 8.1 start screens -->
<meta name="application-name" content="<?php echo osintegration_getoption( 'title', $options ); ?>" />
<meta name="msapplication-TileColor" content="<?php echo osintegration_getoption( 'background-color', $options ); ?>" />
<meta name="msapplication-notification" content="frequency=<?php echo $polling_frequency; ?>;polling-uri=<?php echo $polling_uri; ?>; cycle=1" />
<meta name="msapplication-square310x310logo" content="<?php echo osintegration_getoption( 'img_square_310', $options ); ?>" />
<meta name="msapplication-wide310x150logo" content="<?php echo osintegration_getoption( 'img_wide_310', $options ); ?>" />
<meta name="msapplication-square150x150logo" content="<?php echo osintegration_getoption( 'img_square_150', $options ); ?>" />
<meta name="msapplication-square70x70logo" content="<?php echo osintegration_getoption( 'img_square_70', $options ); ?>" />
<meta name="msapplication-TileImage" content="<?php echo osintegration_getoption( 'img_square_144', $options ); ?>" />

<?php
		}
	// End Windows 8.

	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	// If we're supporting iOS, output the required code now.
	if( osintegration_getoption( 'enableios', $options ) && osintegration_getoption( 'ios_icon_144', $options ) && ( stristr( $user_agent, 'iphone' ) !== FALSE || stristr( $user_agent, 'ipad' ) !== FALSE ) )
		{
		$statusbarstyle = 'black-translucent';
		if( $options['statusbarstyle'] == 1 ) { $statusbarstyle = 'black'; }
		if( $options['statusbarstyle'] == 2 ) { $statusbarstyle = 'default'; }

?>
<!-- For iOS home screen icons -->
<link href="<?php echo osintegration_getoption( 'ios_icon_57', $options ); ?>" rel="apple-touch-icon" sizes="57x57" />
<link href="<?php echo osintegration_getoption( 'ios_icon_114', $options ); ?>" rel="apple-touch-icon" sizes="114x114" />
<link href="<?php echo osintegration_getoption( 'ios_icon_72', $options ); ?>" rel="apple-touch-icon" sizes="72x72" />
<link href="<?php echo osintegration_getoption( 'ios_icon_144', $options ); ?>" rel="apple-touch-icon" sizes="144x144" />

<!-- Override the default page name for iOS -->
<meta name="apple-mobile-web-app-title" content="<?php echo osintegration_getoption( 'title', $options );?>">

<?php

		// If we're supporting iOS web app, output the required code now.
		if( $options['enablewebapp'] )
			{
?>
<!-- For iOS Web App -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $statusbarstyle; ?>" />
<meta name="viewport" content="width=device-width">

<script type="text/javascript">
<?php
if( $options['enablelinkoverride'] )
	{?>
	// If we're in a webapp window, avoid leaving it every time a user clicks on a link.
	if (window.navigator.standalone == true)
		{
		// Capture all clicks on the page.
		jQuery( document ).on("click",
			function( event )
				{
				// Find the root page location.
				var root = (location.protocol + '//' + location.host + location.pathname);

				// If whatever we clicked on has is a link to something on our own site, prevent the webapp window for switching to Safari.
				// Otherwise let it switch to the external site.
			    if( event.srcElement.href.indexOf( root ) == 0 )
					{
					// Stop the default behavior of the browser, which
					// is to change the URL of the page.
					event.preventDefault();

					// Manually change the location of the page to stay in
					// "Standalone" mode and change the URL at the same time.
					location.href = jQuery( event.target ).attr( "href" );
					}
				}
			);
		}

<?php
	} ?>
	(function(){
		var image 				= false;
		var land_image			= false;
		var ipad				= false;
		var userAgent 			= navigator.userAgent;
		var devicePixelRatio 	= window.devicePixelRatio ? window.devicePixelRatio : 0;

		if( userAgent.indexOf( "iPhone" ) > -1)
			{
			if( devicePixelRatio > 1 )
				{
				image = "<?php echo osintegration_getoption( 'ios_web_app_920', $options ); ?>";

				if( window.screen.height == 568 )
					{
					image = "<?php echo osintegration_getoption( 'ios_web_app_1096', $options ); ?>";
					}
				}
			else
				{
				image = "<?php echo osintegration_getoption( 'ios_web_app_460', $options ); ?>";
				}
			}
		else if( userAgent.indexOf( "iPad" ) > -1 )
			{
			ipad = true;

			if( devicePixelRatio > 1 )
				{
				image 		= "<?php echo osintegration_getoption( 'ios_web_app_2008', $options ); ?>";
				land_image 	= "<?php echo osintegration_getoption( 'ios_web_app_2048', $options ); ?>";
				}
			else
				{
				image 		= "<?php echo osintegration_getoption( 'ios_web_app_1004', $options ); ?>";
				land_image 	= "<?php echo osintegration_getoption( 'ios_web_app_1024', $options ); ?>";
				}
			}

		if( image )
			{
			var link 	= document.createElement( "link" );
			link.rel	= "apple-touch-startup-image";
			link.href	= image;

			if( ipad )
				{
				link.media	= "(orientation: portrait)";
				}

			document.getElementsByTagName( "head" )[0].appendChild( link );
			}

		if( land_image )
			{
			var link 	= document.createElement( "link" );
			link.rel	= "apple-touch-startup-image";
			link.href	= image;
			link.media	= "(orientation: landscape)";

			document.getElementsByTagName( "head" )[0].appendChild( link );
			}
	})();
</script>

<?php
			}
			// End web apps.

		}
		// End iOS.

	}

// This function will take two images ($first and $second) and overlay $second on to $first at $x/$y co-ordinates.
// Needs GD or ImageMagic to function, will return FALSE if they don't exist.
function osintegration_composite_images( $first, $second, $x, $y )
	{
	if( !is_readable( $first ) || !is_readable( $second ) ) { return FALSE; }

	// First try using the GD library, then Image Magic, otherwise just fail.
	if( function_exists( 'imagecopy' ) )
		{
		// Load the two PNG's were going to composite together.
		$dest = imagecreatefrompng( $first );
		$src = imagecreatefrompng( $second );

		// Use imagecopy NOT imagecopymerge, otherwise the transparency won't work.
		imagecopy( $dest, $src, $x, $y, 0, 0, imagesx($src), imagesy($src) );

		// Save the merged image to a file.
		imagepng( $dest, $first );

		// Get rid of the working copies.
		imagedestroy( $dest );
		imagedestroy( $src );

		return $first;
		}
	else if( class_exists( 'Imagick' ) )
		{
		$dest = new Imagick();
		$src = new Imagick();

		$dest->readImage( $first );
		$src->readImage( $second );

		$dest->compositeImage( $src, Imagick::COMPOSITE_DEFAULT, $x, $y );

		$dest->flattenImages();

		$dest->writeImage( $first );

		$dest->destroy();
		$src->destroy();

		return FALSE;
		}
	else
		{
		return FALSE;
		}
	}

// This function creates a new PNG file ($filename) of a given size ($x/$y) and fills it with a solid color ($fill = array('R'=>int, 'G'=>int, 'B'=>int)) if $fill is an array.
// Needs GD or ImageMagic to function, will return FALSE if they don't exist.
function osintegration_new_png( $filename, $x, $y, $fill )
	{
	// First try using the GD library, then Image Magic, otherwise just fail.
	if( function_exists( 'imagecreatetruecolor' ) )
		{
		$color = osintegration_html2rgb( $fill );

		// We need to first create a new true color image, not just a standard pallet image, otherwise it won't work later when we try and overlay the logo on to the background.
		$img = imagecreatetruecolor( $x, $y );

		// Check to make sure we were passed an array, if not, don't bother filling in the background.
		if( is_array( $color ) )
			{
			// Get the selected background color to use.
			$newcolor = imagecolorallocate( $img, $color['R'], $color['G'], $color['B'] );

			// Fill the entire new image.
			imagefill( $img, 0, 0, $newcolor );
			}

		// Write the PNG out to a file.
		imagepng( $img, $filename );

		// Free up the color and the temporary image.
		imagecolordeallocate( $img, $newcolor );
		imagedestroy( $img );

		return $filename;
		}
	else if( class_exists( 'Imagick' ) )
		{
		$img = new Imagick();

    	$img->newImage( $x, $y, new ImagickPixel( $fill ), 'PNG32' );
		$img->setFilename( $filename );
		$img->writeImage( $filename );

		$img->destroy();

		return FALSE;
		}
	else
		{
		return FALSE;
		}
	}

// This function generates the XML file for use by MS Live Tiles if it is being generated locally.
function osintegration_outputxmlfeed()
	{
	// Get the current plugin options;
	$options = get_option( OSINTOPTIONNAME );

	$args = array(
					'numberposts' => 3,
					'offset' => 0,
					'category' => 0,
					'orderby' => 'post_date',
					'order' => 'DESC',
					'include' => null,
					'exclude' => null,
					'meta_key' => null,
					'meta_value' => null,
					'post_type' => 'post',
					'post_status' => 'publish',
					'suppress_filters' => true
				);

    $recent_posts = wp_get_recent_posts( $args, ARRAY_A );

	if( $options['localfimage'] )
		{
		$fimages = array( '', '', '' );
		if( array_key_exists( 0, $recent_posts ) ) { $fimages[0] = '			<image id="1" src="' . osintegration_get_post_first_image( $recent_posts[0]['ID'], $recent_posts[0]['post_content'], $options ) . '"/>' . "\n"; }
		if( array_key_exists( 1, $recent_posts ) ) { $fimages[1] = '			<image id="2" src="' . osintegration_get_post_first_image( $recent_posts[1]['ID'], $recent_posts[1]['post_content'], $options ) . '"/>' . "\n"; }
		if( array_key_exists( 2, $recent_posts ) ) { $fimages[2] = '			<image id="3" src="' . osintegration_get_post_first_image( $recent_posts[2]['ID'], $recent_posts[2]['post_content'], $options ) . '"/>' . "\n"; }
		}

	$titles = array( '', '', '' );
	if( array_key_exists( 0, $recent_posts ) ) { $titles[0] = '			<text id="1">' . $recent_posts[0]["post_title"] . '</text>' . "\n"; }
	if( array_key_exists( 1, $recent_posts ) ) { $titles[1] = '			<text id="2">' . $recent_posts[1]["post_title"] . '</text>' . "\n"; }
	if( array_key_exists( 2, $recent_posts ) ) { $titles[2] = '			<text id="3">' . $recent_posts[2]["post_title"] . '</text>' . "\n"; }

	echo '<tile>' . "\n";
	echo '	<visual lang="en-US" version="2">' . "\n";
	echo '		<binding template="TileSquare150x150Text04" branding="logo" fallback="TileSquareImage">' . "\n";
	echo $titles[0];
	echo '		</binding>' . "\n";

	if( $options['localfimage'] )
		{
		echo '		<binding template="TileWide310x150ImageAndText01" branding="logo" fallback="TileWideImage">' . "\n";
		echo $fimages[0];
		echo $titles[0];
		}
	else
		{
		echo '		<binding template="TileWide310x150Text05" branding="logo" fallback="TileWideText05">' . "\n";
		echo $titles[0];
		echo $titles[1];
		echo $titles[2];
		}

	echo '		</binding>' . "\n";


	if( $options['localfimage'] )
		{
		echo '		<binding template="TileSquare310x310SmallImagesAndTextList02" branding="logo">' . "\n";
		echo $fimages[0];
		echo $fimages[1];
		echo $fimages[2];
		}
	else
		{
		echo '		<binding template="TileSquare310x310TextList02" branding="logo">' . "\n";
		}

		echo $titles[0];
		echo $titles[1];
		echo $titles[2];
	echo '		</binding>' . "\n";
	echo '	</visual>' . "\n";
	echo '</tile>' . "\n";

	exit();
	}


function osintegration_get_post_first_image( $postID, $post_content, $options )
	{
	$args = array(
		'numberposts' => 1,
		'order' => 'ASC',
		'post_mime_type' => 'image',
		'post_parent' => $postID,
		'post_status' => null,
		'post_type' => 'attachment',
		);

	if( has_post_thumbnail( $postID ) ) {

		// Get the first child image of the post.
		$attachments = get_children( $args );

		// See if we found any.
		if( is_array( $attachments ) && !empty( $attachments ) )
			{
			// The returned array is NOT zero based, instead the post_id of the attachement is used, so do a foreach loop through the array of 1.
			foreach( $attachments as $attachment )
				{
				return wp_get_attachment_thumb_url( $attachment->ID );
				}
			}
		}

	// We didn't find any attachments, so let's check the content if enabled.
	if( $options['searchbody'] )
		{
		// First process any shortcodes that may be embedded.
		$content = do_shortcode( $post_content );

		if( $content != '' )
			{
			$doc = new DOMDocument();
			$doc->loadHTML( $content );
			$xpath = new DOMXPath( $doc );
			$src = $xpath->evaluate( "string(//img/@src)" );

			if( $src != '' )
				{
				return $src;
				}
			}
		}

	// If enabled, return the square 150x150 image as the default image for the post, assuming we have one.
	if( $options['xmldefaultimage'] && $options['img_square_150'] )
		{
		return $options['img_square_150'];
		}

	// If we still didn't find anything, return a WordPress logo image as a last resort.
	return plugins_url( 'images/wordpress-logo.png', __FILE__ );
	}

// This function converts an HTML style color (#ffffff) to an array ('R'=>int, 'G'=>int, 'B'=>int).
function osintegration_html2rgb( $color )
	{
	// First, check to see if the value starts with a #, if so, get rid of it.
    if( $color[0] == '#' ) { $color = substr($color, 1); }

	// Two formats are supported ffffff and a short form fff.  Fail if the string is in neither format.
    if( strlen( $color ) == 6 )
		{
        list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
		}
    elseif (strlen($color) == 3)
		{
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		}
    else
        return false;

	// Convert the hex values to decimal and return the result array.
    return array( 'R'=>hexdec($r), 'G'=>hexdec($g), 'B'=>hexdec($b) );
	}

