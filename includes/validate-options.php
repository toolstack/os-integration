<?php
function osintegration_validate_options( $input ) {
	// Get the old options for reference.
	$options = get_option( OSINTOPTIONNAME );

	// Sanitize inputs.
	$input['title'] = sanitize_text_field( $input['title'] );
	$input['notification_frequency'] = absint( $input['notification_frequency'] );

	// don't let users shoot themselves in the foot by trying to set a value other than those MS accepts.
	if( ! in_array( $input['notification_frequency'], array( 30, 60, 360, 720, 1440 ) ) ) {
		$input['notification_frequency'] = 360;
	}

	// Create the various image sizes if the image or other options have been changed.
	if( $options['squareimgurl'] != $input['squareimgurl']
		|| $options['wideimgurl'] != $input['wideimgurl']
		|| $options['background-color'] != $input['background-color']
		|| $options['enablefavicon'] != $input['enablefavicon']
		|| $options['faviconpath'] != $input['faviconpath']
		|| $options['widewebapp'] != $input['widewebapp']
		|| $options['logo-position'] != $input['logo-position']
		|| $options['enablepwa'] != $input['enablepwa']
		|| $input['forcerebuild'] )	{
		// If the user forced a rebuild of the images, unset it now so we don't save it later.
		unset( $input['forcerebuild'] );

		// We need a few variables to use later on, set them up now.
		$upload_dir = wp_upload_dir();
		$upload_base_dir = $upload_dir['basedir'];

		$path = trailingslashit( $upload_base_dir ) . 'os-integration/';

		// By default the media selector returns a url, some hosting providers disable remote file wrappers for security,
		// so let's convert the "local" url to a local path.
		$square_image_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $input['squareimgurl'] );
		$wide_image_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $input['wideimgurl'] );

		// Check to make sure the square image is a PNG file.
		if( strtolower( substr( $square_image_path, -4 ) ) != '.png' && $square_image_path != '' ) {
			$input['error_message'] = '<p><b>' . __( 'Error - Square image file is not a PNG!', 'os-integration' ) . '</b></p>';
			return $input;
		}

		// Check to make sure the wide image is a PNG file.
		if( strtolower( substr( $wide_image_path, -4 ) ) != '.png' && $wide_image_path != '' ) {
			$input['error_message'] = '<p><b>' . __( 'Error - Wide image file is not a PNG!', 'os-integration' ) . '</b></p>';

			return $input;
		}

		if( !is_dir( $path ) ) {
			mkdir( $path, null, true );
		}

		// Flush out any old files before we create the new images.
		$files_to_delete = scandir( $path );
		foreach( $files_to_delete as $file ) {
			if( !is_dir( $file ) ) {
				@unlink( $path . $file );
			}
		}

		// Load the square image in to the WordPress image editor and make the required sizes.
		$squareimg = wp_get_image_editor( $square_image_path );

		// Make sure the image exists.
		if( !is_wp_error( $squareimg ) ) {
			$imgsize = $squareimg->get_size();

			if( $imgsize['width'] != $imgsize['height'] || $imgsize['width'] < 450 ) {
				$input['error_message'] .= '<p><b>' . sprintf( 'Error - Square image has incorrect dimensions (%sx%s)!', $imgsize['width'], $imgsize['height'] ) . '</b></p>';
			} else {
				// Save the image as a png in the dedicated os-integration folder before generating variants.
				$info = pathinfo( $input['squareimgurl'] );
				$path = trailingslashit( $upload_base_dir ) . 'os-integration/';
				$filename = $info['filename'];
				$out = $squareimg->save( $path . $filename . '.png', 'image/png' );

				if( !is_wp_error( $out ) ) {
					// Create the image sizes we needed.
					$sizes_array = array(
										array( 'width' => 16, 'height' => 16, 'crop' => true ),
										array( 'width' => 32, 'height' => 32, 'crop' => true ),
										array( 'width' => 48, 'height' => 48, 'crop' => true ),
										array( 'width' => 57, 'height' => 57, 'crop' => true ),
										array( 'width' => 64, 'height' => 64, 'crop' => true ),
										array( 'width' => 70, 'height' => 70, 'crop' => true ),
										array( 'width' => 72, 'height' => 72, 'crop' => true ),
										array( 'width' => 96, 'height' => 96, 'crop' => true ),
										array( 'width' => 114, 'height' => 114, 'crop' => true ),
										array( 'width' => 144, 'height' => 144, 'crop' => true ),
										array( 'width' => 150, 'height' => 150, 'crop' => true ),
										array( 'width' => 160, 'height' => 160, 'crop' => true ),
										array( 'width' => 192, 'height' => 192, 'crop' => true ),
										array( 'width' => 196, 'height' => 196, 'crop' => true ),
										array( 'width' => 230, 'height' => 230, 'crop' => true ),
										array( 'width' => 310, 'height' => 310, 'crop' => true ),
										array( 'width' => 450, 'height' => 450, 'crop' => true )
									);

					$resize = $squareimg->multi_resize( $sizes_array );

					// Save the new image URLs in the plugin options for use when we generate the HTML.
					if ( !is_wp_error( $resize ) ) {
						$base = trailingslashit($upload_dir['baseurl']) . 'os-integration/';

						$i = 0;
						foreach( $sizes_array as $size ) {
							$input['img_square_' . $size['width']] = $base . $resize[$i]['file'];
							$i++;
						}
					} else {
						$input['error_message'] = '<b>' . sprintf( __( 'Error Generating Square Images: %s%s', 'os-integration' ), '</b>', $resize->get_error_message() );
					}
				} else {
					$input['error_message'] = '<b>' . sprintf( __( 'Error Converting Square Image: %s%S', 'os-integration' ), '</b>', $out->get_error_message() );
				}
			}
		} else {
			$input['error_message'] = '<b>' . sprintf( __( 'Error - Could not edit square image file: %s%s', 'os-integration' ), '</b>', $squareimg->get_error_message() ) . '<br><br>';
			$input['error_message'] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . sprintf( __( 'URL: %s', 'os-integration' ), $input['imgurl'] ) . '<br>';
			$input['error_message'] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . sprintf( __( 'Path: %s', 'os-integration' ), $image_path ) . '<br>';
		}

		// If a wide image doesn't exist, create one from the square image (assuming of course it exists :)
		if( !file_exists( $wide_image_path ) && file_exists( $square_image_path ) ) {
			// Get the file path information for the square image.
			$img_path_info = pathinfo($square_image_path);

			$wide_image_path = trailingslashit( $upload_base_dir ) . 'os-integration/' . $img_path_info['filename'] . '-wide.' . $img_path_info['extension'];

			// Get the image size so we can calculate the wide image size.
			$imgsize = $squareimg->get_size();

			$wide_imgsize = $imgsize;
			$wide_imgsize['width'] = $imgsize['height'] * (451 / 219);

			// Create the blank background image.
			osintegration_new_png( $wide_image_path, $wide_imgsize['width'], $wide_imgsize['height'], $input['background-color'] );
			// Determine the location of the logo on the background.
			$x = (int)( ( $wide_imgsize['width'] - $imgsize['width'] ) / 2 );
			$y = 0;

			// Add the logo to the background image.
			osintegration_composite_images( $wide_image_path, $square_image_path, $x, $y );

			// Store the url
			$wide_image_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $wide_image_path );
			$input['wideimgurl'] = $wide_image_url;
		}
			
		// Load the wide image in to the WordPress image editor and make the required sizes.
		$wideimg = wp_get_image_editor( $wide_image_path );

		// Make sure the image exists.
		if( !is_wp_error( $wideimg ) ) {
			$imgsize = $wideimg->get_size();

			if( $imgsize['height'] < 219 || $imgsize['width'] < 451 ) {
				$input['error_message'] .= '<p><b>' . sprintf( 'Error - Wide image has incorrect dimensions (%sx%s)!', $imgsize['width'], $imgsize['height'] ) . '</b></p>';
			} else {
				// Save the image as a png in the dedicated os-integration folder before generating variants.
				if( $square_image_path != $wide_image_path ) {
					$info = pathinfo( $input['wideimgurl'] );
					$path = trailingslashit( $upload_base_dir ) . 'os-integration/' . $info['filename'];
					$out = $wideimg->save( $path . '.png', 'image/png' );
				}

				if( !is_wp_error( $out ) ) {
					// Create the image sizes we needed.
					$sizes_array = array(
										array ( 'width' => 96, 'height' => 46, 'crop' => true ),
										array ( 'width' => 155, 'height' => 75, 'crop' => true ),
										array ( 'width' => 196, 'height' => 95, 'crop' => true ),
										array ( 'width' => 230, 'height' => 112, 'crop' => true ),
										array ( 'width' => 256, 'height' => 160, 'crop' => true ),
										array ( 'width' => 310, 'height' => 150, 'crop' => true ),
										array ( 'width' => 450, 'height' => 218, 'crop' => true )
									);

					$resize = $wideimg->multi_resize( $sizes_array );

					// Save the new image URLs in the plugin options for use when we generate the HTML.
					if ( !is_wp_error( $resize ) ) {
						$base = trailingslashit( $upload_dir['baseurl'] ) . 'os-integration/';
						$input['img_wide_96']  = $base . $resize[0]['file'];
						$input['img_wide_155'] = $base . $resize[1]['file'];
						$input['img_wide_196'] = $base . $resize[2]['file'];
						$input['img_wide_230'] = $base . $resize[3]['file'];
						$input['img_wide_256'] = $base . $resize[4]['file'];
						$input['img_wide_310'] = $base . $resize[5]['file'];
						$input['img_wide_450'] = $base . $resize[6]['file'];
					} else {
						$input['error_message'] = '<b>' . sprintf( __( 'Error Generating Wide Images: %s%s', 'os-integration' ), '</b>', $resize->get_error_message() );
					}
				} else {
					$input['error_message'] = '<b>' . sprintf( __( 'Error Converting Wide Image: %s%s', 'os-integration' ), '</b>', $out->get_error_message() );
				}
			}
		} else {
			$input['error_message'] = '<b>' . sprintf( __( 'Error - Could not edit wide image file: %s%s', 'os-integration' ), '</b>', $wideimg->get_error_message() ) . '<br><br>';
			$input['error_message'] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . sprintf( __( 'URL: %s', 'os-integration' ), $input['imgurl'] ) . '<br>';
			$input['error_message'] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . sprintf( __( 'Path: %s', 'os-integration' ), $image_path ) . '<br>';
			}

		// Make sure we didn't have an error above.
		if( array_key_exists( 'error_message', $input ) && $input['error_message'] == '' ) {
			// Create the iOS icon and web app backgrounds.
			$path = trailingslashit( $upload_base_dir ) . 'os-integration/';
			$base = trailingslashit($upload_dir['baseurl']) . 'os-integration/';

			if( $input['widewebapp'] ) {
				$iOSfilenames = array(
										array( 'tag' => 'ios_icon_', 'name' => $path . 'iOS-Icon-57x57.png', 'x' => 57, 'y' => 57, 'logo' => $path . basename( $input['img_square_57'] ), 'logo-position' => 1, 'logo-x' => 57, 'logo-y' => 57 ),
										array( 'tag' => 'ios_icon_', 'name' => $path . 'iOS-Icon-72x72.png', 'x' => 72, 'y' => 72, 'logo' => $path . basename( $input['img_square_72'] ), 'logo-position' => 1, 'logo-x' => 72, 'logo-y' => 72 ),
										array( 'tag' => 'ios_icon_', 'name' => $path . 'iOS-Icon-114x114.png', 'x' => 114, 'y' => 114, 'logo' => $path . basename( $input['img_square_114'] ), 'logo-position' => 1, 'logo-x' => 114, 'logo-y' => 114 ),
										array( 'tag' => 'ios_icon_', 'name' => $path . 'iOS-Icon-144x144.png', 'x' => 144, 'y' => 144, 'logo' => $path . basename( $input['img_square_144'] ), 'logo-position' => 1, 'logo-x' => 144, 'logo-y' => 144 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-320x460.png', 'x' => 320, 'y' => 460, 'logo' => $path . basename( $input['img_wide_96'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 96, 'logo-y' => 46 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-640x920.png', 'x' => 640, 'y' => 920, 'logo' => $path . basename( $input['img_wide_196'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 196, 'logo-y' => 95 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-640x1096.png', 'x' => 640, 'y' => 1096, 'logo' => $path . basename( $input['img_wide_196'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 196, 'logo-y' => 95 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-768x1004.png', 'x' => 768, 'y' => 1004, 'logo' => $path . basename( $input['img_wide_230'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 230, 'logo-y' => 112 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-748x1024.png', 'x' => 748, 'y' => 1024, 'logo' => $path . basename( $input['img_wide_230'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 230, 'logo-y' => 112 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-1536x2008.png', 'x' => 1536, 'y' => 2008, 'logo' => $path . basename( $input['img_wide_450'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 450, 'logo-y' => 218 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-1496x2048.png', 'x' => 1496, 'y' => 2048, 'logo' => $path . basename( $input['img_wide_450'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 450, 'logo-y' => 218 )
									);
			} else {
				$iOSfilenames = array(
										array( 'tag' => 'ios_icon_', 'name' => $path . 'iOS-Icon-57x57.png', 'x' => 57, 'y' => 57, 'logo' => $path . basename( $input['img_square_57'] ), 'logo-position' => 1, 'logo-x' => 57, 'logo-y' => 57 ),
										array( 'tag' => 'ios_icon_', 'name' => $path . 'iOS-Icon-72x72.png', 'x' => 72, 'y' => 72, 'logo' => $path . basename( $input['img_square_72'] ), 'logo-position' => 1, 'logo-x' => 72, 'logo-y' => 72 ),
										array( 'tag' => 'ios_icon_', 'name' => $path . 'iOS-Icon-114x114.png', 'x' => 114, 'y' => 114, 'logo' => $path . basename( $input['img_square_114'] ), 'logo-position' => 1, 'logo-x' => 114, 'logo-y' => 114 ),
										array( 'tag' => 'ios_icon_', 'name' => $path . 'iOS-Icon-144x144.png', 'x' => 144, 'y' => 144, 'logo' => $path . basename( $input['img_square_144'] ), 'logo-position' => 1, 'logo-x' => 144, 'logo-y' => 144 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-320x460.png', 'x' => 320, 'y' => 460, 'logo' => $path . basename( $input['img_square_96'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 96, 'logo-y' => 96 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-640x920.png', 'x' => 640, 'y' => 920, 'logo' => $path . basename( $input['img_square_196'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 196, 'logo-y' => 196 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-640x1096.png', 'x' => 640, 'y' => 1096, 'logo' => $path . basename( $input['img_square_196'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 196, 'logo-y' => 196 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-768x1004.png', 'x' => 768, 'y' => 1004, 'logo' => $path . basename( $input['img_square_230'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 230, 'logo-y' => 230 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-748x1024.png', 'x' => 748, 'y' => 1024, 'logo' => $path . basename( $input['img_square_230'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 230, 'logo-y' => 230 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-1536x2008.png', 'x' => 1536, 'y' => 2008, 'logo' => $path . basename( $input['img_square_450'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 450, 'logo-y' => 450 ),
										array( 'tag' => 'ios_web_app_', 'name' => $path . 'iOS-Web-App-1496x2048.png', 'x' => 1496, 'y' => 2048, 'logo' => $path . basename( $input['img_square_450'] ), 'logo-position' => $input['logo-position'], 'logo-x' => 450, 'logo-y' => 450 )
									);
			}

			foreach( $iOSfilenames as $item ) {
				// Create the blank background image.
				osintegration_new_png( $item['name'], $item['x'], $item['y'], $input['background-color'] );

				// Determine the location of the logo on the background.
				list( $x, $y ) = osintegration_get_logo_position( $item['x'], $item['y'], $item['logo-position'], $item['logo-x'], $item['logo-y'] );

				// Add the logo to the background image.
				osintegration_composite_images( $item['name'], $item['logo'], $x, $y );

				// Store the url
				$desc = $item['tag'] . $item['y'];
				$input[$desc] = $base . basename( $item['name'] );
			}

			if( $input['enablefavicon'] ) {
				// Generate the ICO file
				require_once( dirname( __FILE__ ) . '/includes/php-ico/class-php-ico.php' );

				$destination = dirname( __FILE__ ) . '/example.ico';

				$ico_lib = new PHP_ICO();

				if( $input['favicon96'] ) { $ico_lib->add_image( $path . basename( $input['img_square_96'] ), array( 96, 96 ) ); }
				if( $input['favicon64'] ) { $ico_lib->add_image( $path . basename( $input['img_square_64'] ), array( 64, 64 ) ); }
				$ico_lib->add_image( $path . basename( $input['img_square_32'] ), array( 32, 32 ) );
				$ico_lib->add_image( $path . basename( $input['img_square_16'] ), array( 16, 16 ) );

				$ico_lib->save_ico( trailingslashit( $input['faviconpath'] ) . 'favicon.ico' );
			}
		}

		// Deal with a user override of individual items
		foreach( $input as $key => $value ) {
			if( substr( $key, 0, 7 ) == 'adv_' ) {
				$basekey = substr( $key, 4 );

				if( $value != '' ) {
					$input[$basekey] = $value;
				}
			}
		}

		// Generate PWA Manifest
		if( $input['enablepwa'] ) {
			$path = trailingslashit( $upload_base_dir ) . 'os-integration/';

			$manifest['shortname']        = osintegration_getoption( 'pwashortname', $input, get_bloginfo( 'name' ) );
			$manifest['name']             = osintegration_getoption( 'pwaname', $input, get_bloginfo( 'name' ) );
			$manifest['description']      = osintegration_getoption( 'pwadesc', $input, get_bloginfo( 'description' ) );
			$manifest['start_url']        = osintegration_getoption( 'pwalandingurl', $input, get_bloginfo( 'url' ) );
			$manifest['background_color'] = osintegration_getoption( 'background-color', $input, '#000000' );
			$manifest['display']          = osintegration_getoption( 'pwadisplaymode', $input, 'standalone' );
			$manifest['orientation']      = osintegration_getoption( 'pwaorientation', $input, 'any' );

			if( osintegration_getoption( 'pwathemecolor', $input ) ) {
				$manifest['theme_color']      = $input['pwathemecolor'];
			}

			$manifest['icons']            = array();

			$sizes_array = array(
								array( 'width' => 16, 'height' => 16, 'crop' => true ),
								array( 'width' => 32, 'height' => 32, 'crop' => true ),
								array( 'width' => 48, 'height' => 48, 'crop' => true ),
								array( 'width' => 64, 'height' => 64, 'crop' => true ),
								array( 'width' => 72, 'height' => 72, 'crop' => true ),
								array( 'width' => 96, 'height' => 96, 'crop' => true ),
								array( 'width' => 144, 'height' => 144, 'crop' => true ),
								array( 'width' => 192, 'height' => 192, 'crop' => true ),
								array( 'width' => 230, 'height' => 230, 'crop' => true ),
								array( 'width' => 310, 'height' => 310, 'crop' => true ),
								array( 'width' => 450, 'height' => 450, 'crop' => true )
							);

			foreach( $sizes_array as $size ) {
				$icon['src']  = $input['img_square_' . $size['width']];
				$icon['type'] = 'image/png';
				$icon['sizes'] = $size['width'] . 'x' . $size['width'];

				$manifest['icons'][] = $icon;
			}

			file_put_contents( $path . 'manifest.json', json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . PHP_EOL );
		}
	} else {
		// If we're not generating new images, copy the old image settings over from the previous options array.
		foreach( $options as $tag => $item ) {
			if( !isset( $input[$tag] ) ) {
				$chop = substr( $tag, 0, 4 );

				if( $chop == 'img_' || $chop == 'ios_' ) {
					$input[$tag] = $options[$tag];
				}
			}
		}
	}

	return $input;
}

// This function determines the local to place the logo on the iOS web app load screens.
// 		$x/$y is the size of the load screen.
// 		$logopostion is the location of the logo (see below).
//		$logox/$logoy is the size of the logo.
//
// Positions:
//		1 = top left
//		2 = top center
//		3 = top right
//		4 = middle left
//		5 = middle center
//		6 = middle right
//		7 = bottom left
//		8 = bottom center
//		9 = bottom right
//
function osintegration_get_logo_position( $x, $y, $logoposition, $logox, $logoy ) {
	// Initialize our position.
	$posx = $posy = 0;

	switch( $logoposition ) {
		case 1:		// top left
			$posx = 0;
			$posy = 0;

			break;
		case 2:		// top center
			$posx = ( $x - $logox ) / 2;
			$posy = 0;

			break;
		case 3:		// top right
			$posx = ( $x - $logox );
			$posy = 0;

			break;
		case 4:		// middle left
			$posx = 0;
			$posy = ( $y - $logoy ) / 2;

			break;
		case 5:		// middle center
			$posx = ( $x - $logox ) / 2;
			$posy = ( $y - $logoy ) / 2;

			break;
		case 6:		// middle right
			$posx = ( $x - $logox );
			$posy = ( $y - $logoy ) / 2;

			break;
		case 7:		// bottom left
			$posx = 0;
			$posy = ( $y - $logoy );

			break;
		case 8:		// bottom center
			$posx = ( $x - $logox ) / 2;
			$posy = ( $y - $logoy );

			break;
		case 9:		// bottom right
			$posx = ( $x - $logox );
			$posy = ( $y - $logoy );

			break;
	}

	return array( $posx, $posy );
}
