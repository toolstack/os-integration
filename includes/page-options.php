<?php
function osintegration_options_page() {
	$options = get_option( OSINTOPTIONNAME );

	if( !array_key_exists( 'notification_frequency', $options ) ) {
		$options['notification_frequency'] = 360;
	}

	if( !function_exists( 'imagecreatetruecolor' ) && !class_exists( 'Imagick' ) ) {
		echo '<div id="message" class="error fade"><p>' . __( 'PHP GD/Image Magic library is not installed, you will not be able to generate images!' ) . '</p></div>' . PHP_EOL;
	}

?>
<div class="wrap">
	<style type="text/css">
		table.form-table {
			clear: none;
			float: left;
			width: 100%;
		}
	</style>
	<script type="text/javascript">jQuery(document).ready(function() { jQuery("#tabs").tabs(); jQuery("#tabs").tabs("option", "active",1);} );</script>
	<h2><?php _e( 'OS Integration Settings', 'os-integration' ); ?></h2>
	<br>
	<?php if( osintegration_getoption( 'error_message', $options ) ) { echo '<div class="error is-dismissible">' . osintegration_getoption( 'error_message', $options ) . '</div>'; } ?>
	<form method="post" action="options.php" >
		<?php settings_fields( 'osintegration_plugin_options' ); ?>
		<div id="tabs">
			<ul>
				<li><a href="#fragment-0"><span><?php _e( 'Current Images', 'os-integration' );?></span></a></li>
				<li><a href="#fragment-1"><span><?php _e( 'General', 'os-integration' );?></span></a></li>
				<li><a href="#fragment-2"><span><?php _e( 'Fav Icon', 'os-integration' );?></span></a></li>
				<li><a href="#fragment-3"><span><?php _e( 'Progress Web App', 'os-integration' );?></span></a></li>
				<li><a href="#fragment-4"><span><?php _e( 'Windows', 'os-integration' );?></span></a></li>
				<li><a href="#fragment-5"><span><?php _e( 'iOS', 'os-integration' );?></span></a></li>
				<li><a href="#fragment-6"><span><?php _e( 'Advanced', 'os-integration' );?></span></a></li>
				<li><a href="#fragment-7"><span><?php _e( 'About', 'os-integration' );?></span></a></li>
			</ul>

			<div id="fragment-0">
				<h2><?php _e( 'ICO', 'os-integration' ); ?></h2>
				<hr>
				<img src="<?php echo osintegration_getoption( 'img_square_16', $options, '' );?>">
				<br>
				<br>

				<h2><?php _e( 'Fav Icons', 'os-integration' ); ?></h2>
				<hr>
				<img src="<?php echo osintegration_getoption( 'img_square_150', $options, '' );?>">
				<br>
				<br>

				<h2><?php _e( 'Live Tiles', 'os-integration' ); ?></h2>
				<hr>
				<img src="<?php echo osintegration_getoption( 'img_square_150', $options, '' );?>">
				<br>
				<br>
				<img src="<?php echo osintegration_getoption( 'img_wide_310', $options, '' );?>">
				<br>
				<br>

				<h2><?php _e( 'iOS Icons', 'os-integration' ); ?></h2>
				<hr>
				<img src="<?php echo osintegration_getoption( 'ios_icon_144', $options, '' );?>">
				<br>
				<br>

				<h2><?php _e( 'iOS Web App', 'os-integration' ); ?></h2>
				<hr>
				<img src="<?php echo osintegration_getoption( 'ios_web_app_460', $options, '' );?>">
				<br>
				<br>

			</div>

			<div id="fragment-1">
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Square Image', 'os-integration' ); ?><br/></th>
						<td>
							<input type="url" id="squareimgurl" name="osintegration_options[squareimgurl]" value="<?php echo osintegration_getoption( 'squareimgurl', $options, '' ); ?>" size="100"/>
							<br>
							<input type="button" class="button" name="square_img_button" id="square_img_button" value="Select Image" />
							<br><br>
							<em><?php _e( 'This image will be cropped and resized for the various image sizes, you must use a PNG image larger than 450x450 px.', 'os-integration' ); ?></em>
						</td>
						<td rowspan="2" width="150px" align="center" valign="top">
							<h2><?php _e( 'Preview', 'os-integration' ); ?></h2>
							<hr>
							<div id="osintbgpreview" style="background-color: <?php echo osintegration_getoption( 'background-color', $options, "" ); ?>; width: 150px; height: 150px;">
								<img id="osintimgpreview" width="150px" height="150px" src="<?php echo osintegration_getoption( 'squareimgurl', $options, '' ); ?>">
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Wide Image', 'os-integration' ); ?><br/></th>
						<td>
							<input type="url" id="wideimgurl" name="osintegration_options[wideimgurl]" value="<?php echo osintegration_getoption( 'wideimgurl', $options, '' ); ?>" size="100"/>
							<br>
							<input type="button" class="button" name="wide_img_button" id="wide_img_button" value="Select Image" />
							<br><br>
							<em><?php _e( 'This image will be cropped and resized for the various image sizes, you must use a PNG image larger than 450x218 px.', 'os-integration' ); ?></em>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Background Color', 'os-integration' ); ?></th>
						<td colspan="2">
							<input type="text" id="color" class="color-field" name="osintegration_options[background-color]" value="<?php echo osintegration_getoption( 'background-color', $options, '' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"<?php _e( '>Site Title', 'os-integration' ); ?></th>
						<td colspan="2">
							<input type="text" id="title" name="osintegration_options[title]" value="<?php echo osintegration_getoption( 'title', $options, '' ); ?>" />
							<br><br>
							<em><?php _e( 'This will be used in Windows Live Tiles and iOS instead of the default WordPress site title.', 'os-integration' ); ?></em>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<p class="submit">
								<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'os-integration' ); ?>" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-2">
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Enable Fav Icon', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="enablefavicon" name="osintegration_options[enablefavicon]"<?php if( osintegration_getoption( 'enablefavicon', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Fav Icon Path', 'os-integration' ); ?></th>
						<td>
							<input type="text" id="faviconpath" name="osintegration_options[faviconpath]" value="<?php if( osintegration_getoption( 'faviconpath', $options ) ) { echo osintegration_getoption( 'faviconpath', $options, '' ); } else { echo str_replace( '\\', '/', ABSPATH ); } ?>" size="50"/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Include 64px Image', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="favicon64" name="osintegration_options[favicon64]"<?php if( osintegration_getoption( 'favicon64', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Include 96px Image', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="favicon96" name="osintegration_options[favicon96]"<?php if( osintegration_getoption( 'favicon96', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'os-integration' ); ?>" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-3">
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Enable Progressive Web App', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="enablepwa" name="osintegration_options[enablepwa]"<?php if( osintegration_getoption( 'enablepwa', $options ) ) { echo " CHECKED"; } ?>/>
							<?php
								if( strtolower( substr( get_bloginfo( 'url' ), 0, 8 ) ) !== 'https://' ) {
							?>
							<br>
							<br>
							<i><?php _e( 'NOTE: Progressive Web Apps require your site to be delivered over https, you can still enable PWA if you do not have https enabled, but the clients will not see the "Add to home screen" option in their browser.', 'os-integration' ); ?></i>
							<?php
								}
							?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Short Name', 'os-integration' ); ?></th>
						<td>
							<input type="text" id="pwashortname" name="osintegration_options[pwashortname]" value="<?php if( osintegration_getoption( 'pwashortname', $options ) ) { echo osintegration_getoption( 'pwashortname', $options, '' ); } else { echo substr( get_bloginfo( 'name' ), 0, 12 ); } ?>" size="20"/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Name', 'os-integration' ); ?></th>
						<td>
							<input type="text" id="pwaname" name="osintegration_options[pwaname]" value="<?php if( osintegration_getoption( 'pwaname', $options ) ) { echo osintegration_getoption( 'pwaname', $options, '' ); } else { echo get_bloginfo( 'name' ); } ?>" size="50"/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Description', 'os-integration' ); ?></th>
						<td>
							<input type="text" id="pwadesc" name="osintegration_options[pwadesc]" value="<?php if( osintegration_getoption( 'pwadesc', $options ) ) { echo osintegration_getoption( 'pwadesc', $options, '' ); } else { echo get_bloginfo( 'description' ); } ?>" size="50"/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Landing URL', 'os-integration' ); ?></th>
						<td>
							<input type="url" id="pwalandingurl" name="osintegration_options[pwalandingurl]" value="<?php if( ! isset( $options['pwalandingurl'] ) ) { echo get_bloginfo( 'url' ); } else { echo $options['pwalandingurl']; } ?>" size="50" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Display mode', 'os-integration' ); ?></th>
						<td><select name="osintegration_options[pwadisplaymode]">
								<option value="browser" <?php selected( $options['pwadisplaymode'], 'browser' ); ?>><?php _e( 'Browser', 'os-integration' ); ?></option>
								<option value="fullscreen" <?php selected( $options['pwadisplaymode'], 'fullscreen' ); ?>><?php _e( 'Fullscreen', 'os-integration' ); ?></option>
								<option value="minimal-ui" <?php selected( $options['pwadisplaymode'], 'minimal-ui' ); ?>><?php _e( 'Minimal UI', 'os-integration' ); ?></option>
								<option value="standalone" <?php selected( $options['pwadisplaymode'], 'standalone' ); ?>><?php _e( 'Standalone', 'os-integration' ); ?></option>
							</select>
						</p></td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Orientation', 'os-integration' ); ?></th>
						<td><select name="osintegration_options[pwaorientation]">
								<option value="any" <?php selected( $options['pwaorientation'], 'any' ); ?>><?php _e( 'Any', 'os-integration' ); ?></option>
								<option value="landscape" <?php selected( $options['pwaorientation'], 'landscape' ); ?>><?php _e( 'Landscape', 'os-integration' ); ?></option>
								<option value="landscape-primary" <?php selected( $options['pwaorientation'], 'landscape-primary' ); ?>><?php _e( 'Landscape primary', 'os-integration' ); ?></option>
								<option value="landscape-secondary" <?php selected( $options['pwaorientation'], 'landscape-secondary' ); ?>><?php _e( 'Landscape Secondary', 'os-integration' ); ?></option>
								<option value="portrait" <?php selected( $options['pwaorientation'], 'portrait' ); ?>><?php _e( 'Portrait', 'os-integration' ); ?></option>
								<option value="portrait-primary" <?php selected( $options['pwaorientation'], 'portrait-primary' ); ?>><?php _e( 'Portrait primary', 'os-integration' ); ?></option>
								<option value="portrait-secondary" <?php selected( $options['pwaorientation'], 'portrait-secondary' ); ?>><?php _e( 'Portrait secondary', 'os-integration' ); ?></option>
							</select>
						</p></td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Theme Color', 'os-integration' ); ?></th>
						<td colspan="2">
							<input type="text" id="pwathemecolor" class="color-field" name="osintegration_options[pwathemecolor]" value="<?php echo osintegration_getoption( 'pwathemecolor', $options, '' ); ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<p class="submit">
								<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'os-integration' ); ?>" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-4">
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Enable Live Tile', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="enablelivetile" name="osintegration_options[enablelivetile]"<?php if( osintegration_getoption( 'enablelivetile', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'RSS Feed URL', 'os-integration' ); ?></th>
						<td>
							<input type="url" id="rssurl" name="osintegration_options[rssurl]" value="<?php if( ! isset( $options['rssurl'] ) ) { echo  get_bloginfo( 'rss2_url' ); } else { echo $options['rssurl']; } ?>" size="50" />
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Update Interval', 'os-integration' ); ?></th>
						<td><select name="osintegration_options[notification_frequency]">
								<option value="30" <?php selected( $options['notification_frequency'], 30 ); ?>>30 minutes</option>
								<option value="60" <?php selected( $options['notification_frequency'], 60 ); ?>>1 hour</option>
								<option value="360" <?php selected( $options['notification_frequency'], 360 ); ?>>6 hours</option>
								<option value="720" <?php selected( $options['notification_frequency'], 720 ); ?>>12 hours</option>
								<option value="1440" <?php selected( $options['notification_frequency'], 1440 ); ?>>1 day</option>
							</select>
						</p></td>
					</tr>
					<tr>
						<th scope="row" colspan=2><h2><?php _e( 'Local XML File', 'os-integration' ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Enable Local XML', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="localxml" name="osintegration_options[localxml]"<?php if( osintegration_getoption( 'localxml', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Include featured image', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="localfimage" name="osintegration_options[localfimage]"<?php if( osintegration_getoption( 'localfimage', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Search body for featured image', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="searchbody" name="osintegration_options[searchbody]"<?php if( osintegration_getoption( 'searchbody', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Use square image if no image found', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="xmldefaultimage" name="osintegration_options[xmldefaultimage]"<?php if( osintegration_getoption( 'xmldefaultimage', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'os-integration' ); ?>" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-5">
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Enable iOS Support', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="enableios" name="osintegration_options[enableios]"<?php if( osintegration_getoption( 'enableios', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Enable Web App Support', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="enablewebapp" name="osintegration_options[enablewebapp]"<?php if( osintegration_getoption( 'enablewebapp', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Enable Link Override', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="enablelinkoverride" name="osintegration_options[enablelinkoverride]"<?php if( osintegration_getoption( 'enablelinkoverride', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Use wide image for web app loading screen', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="widewebapp" name="osintegration_options[widewebapp]"<?php if( osintegration_getoption( 'widewebapp', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Web App Logo Location', 'os-integration' ); ?></th>
						<td>
							<?php $options['logo-position'] = osintegration_getoption( 'logo-position', $options, 1 ); ?>
							<select name="osintegration_options[logo-position]">
								<option value="1" <?php selected( $options['logo-position'], 1 ); ?>>Top Left</option>
								<option value="2" <?php selected( $options['logo-position'], 2 ); ?>>Top Middle</option>
								<option value="3" <?php selected( $options['logo-position'], 3 ); ?>>Top Right</option>
								<option value="4" <?php selected( $options['logo-position'], 4 ); ?>>Center Left</option>
								<option value="5" <?php selected( $options['logo-position'], 5 ); ?>>Center Middle</option>
								<option value="6" <?php selected( $options['logo-position'], 6 ); ?>>Center Right</option>
								<option value="7" <?php selected( $options['logo-position'], 7 ); ?>>Bottom Left</option>
								<option value="8" <?php selected( $options['logo-position'], 8 ); ?>>Bottom Middle</option>
								<option value="9" <?php selected( $options['logo-position'], 9 ); ?>>Bottom Right</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Web App Status Bar Style', 'os-integration' ); ?></th>
						<td>
							<select name="osintegration_options[statusbarstyle]">
								<?php $options['statusbarstyle'] = osintegration_getoption( 'statusbarstyle', $options, 2 ); ?>
								<option value="2" <?php selected( $options['statusbarstyle'], 2 ); ?>>Default</option>
								<option value="1" <?php selected( $options['statusbarstyle'], 1 ); ?>>Black</option>
								<option value="0" <?php selected( $options['statusbarstyle'], 0 ); ?>>Translucent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'os-integration' ); ?>" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-6">
				<table class="form-table">
<?php GLOBAL $wp_version; if( version_compare( $wp_version, '4.2.99', '>' ) ) { ?>
				<tr>
						<th scope="row"><?php _e( 'Allow WordPress Site Icon', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="wpsiteiconmeta" name="osintegration_options[wpsiteiconmeta]"<?php if( osintegration_getoption( 'wpsiteiconmeta', $options ) ) { echo " CHECKED"; } ?>/><br>
							<br>
							<i><?php _e( "OS Integration will override WordPress's Site Icon settings and strip the meta information from the page headers.  If you wish to use WordPress's Site Icons, you can override this behaviour by checking this option.", 'os-integration' ); ?></i>
						</td>
					</tr>
<?php } ?>
					<tr>
						<th scope="row"><?php _e( 'Force rebuild', 'os-integration' ); ?></th>
						<td>
							<input type="checkbox" id="forcerebuild" name="osintegration_options[forcerebuild]"/><br>
							<br>
							<i><?php _e( 'OS Integration only builds new icons/images when the selected square/wide images change, this option will force a one time rebuild of everything when you select "Save Changes".', 'os-integration' ); ?></i>
						</td>
					</tr>
					<tr>
						<td colspan=2>
							<h2><?php _e( 'Override individual image files to be used', 'os-integration' ); ?></h2>
						</td>
					</tr>
<?php
foreach( $options as $key => $option ) {
	if( substr( $key, 0, 4 ) == 'img_' || substr( $key, 0, 4) == 'ios_' ) {
?>
					<tr>
						<th scope="row"><?php echo $key; ?>:</th>
						<td>
							<input type="url" id="adv_<?php echo $key;?>" name="osintegration_options[adv_<?php echo $key;?>]" value="<?php echo osintegration_getoption( 'adv_' . $key, $options, '' );?>" size="100"/>
						</td>
					</tr>
<?php
	}
}
?>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'os-integration' ); ?>" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-7">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<td scope="row" align="center"><img src="<?php echo plugins_url( 'os-integration/images/logo-250.png' ); ?>"></td>
						</tr>

						<tr valign="top">
							<td scope="row" align="center"><h2><?php echo sprintf( __( 'OS Integration V%s', 'os-integration' ), OSINTVER ); ?></h2></td>
						</tr>

						<tr valign="top">
							<td scope="row" align="center"><p>by <a href="https://toolstack.com">Greg Ross</a></p></td>
						</tr>

						<tr valign="top">
							<td scope="row" align="center"><hr /></td>
						</tr>

						<tr valign="top">
							<td scope="row" colspan="2"><h2><?php _e( 'Rate and Review at WordPress.org' ); ?></h2></td>
						</tr>

						<tr valign="top">
							<td scope="row" colspan="2"><?php _e( 'Thanks for installing OS Integration, I encourage you to submit a ' );?> <a href="http://wordpress.org/support/view/plugin-reviews/os-integration" target="_blank"><?php _e( 'rating and review' ); ?></a> <?php _e( 'over at WordPress.org.  Your feedback is greatly appreciated!' );?></td>
						</tr>

						<tr valign="top">
							<td scope="row" colspan="2"><h2><?php _e( 'Support' ); ?></h2></td>
						</tr>

						<tr valign="top">
							<td scope="row" colspan="2">
								<p><?php _e( 'Here are a few things to do submitting a support request:' ); ?></p>

								<ul style="list-style-type: disc; list-style-position: inside; padding-left: 25px;">
									<li><?php echo sprintf( __( 'Have you read the %s?', 'os-integration' ), '<a title="' . __( 'FAQs', 'os-integration' ) . '" href="http://os-integration.com/?page_id=19" target="_blank">' . __( 'FAQs', 'os-integration' ). '</a>');?></li>
									<li><?php echo sprintf( __( 'Have you search the %s for a similar issue?', 'os-integration' ), '<a href="http://wordpress.org/support/plugin/os-integration" target="_blank">' . __( 'support forum', 'os-integration' ) . '</a>');?></li>
									<li><?php _e( 'Have you search the Internet for any error messages you are receiving?' );?></li>
									<li><?php _e( 'Make sure you have access to your PHP error logs.' );?></li>
								</ul>

								<p><?php _e( 'And a few things to double-check:' );?></p>

								<ul style="list-style-type: disc; list-style-position: inside; padding-left: 25px;">
									<li><?php _e( 'Have you double checked the plugin settings?' );?></li>
									<li><?php _e( 'Do you have all the required PHP extensions installed?' );?></li>
									<li><?php _e( 'Are you getting a blank or incomplete page displayed in your browser?  Did you view the source for the page and check for any fatal errors?' );?></li>
									<li><?php _e( 'Have you checked your PHP and web server error logs?' );?></li>
								</ul>

								<p><?php _e( 'Still not having any luck?' );?> <?php echo sprintf( __( 'Then please open a new thread on the %s.', 'os-integration' ), '<a href="http://wordpress.org/support/plugin/os-integration" target="_blank">' . __( 'WordPress.org support forum', 'os-integration' ) . '</a>');?></p>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>

	</form>
</div>
<?php
}
