<?php
function osintegration_options_page()
	{
	$options = get_option( OSINTOPTIONNAME );

	if( !array_key_exists( 'notification_frequency', $options ) )
		{
		$options['notification_frequency'] = 360;
		}

	if( !function_exists( 'imagecreatetruecolor' ) && !class_exists( 'Imagick' ) )
		{
		echo '<div id="message" class="error fade"><p>' . __( 'PHP GD/Image Magic library is not installed, you will not be able to generate images!' ) . '</p></div>' . "\n";
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
	<h2>OS Integration Settings</h2>
	<br>
	<?php if( osintegration_getOption( 'error_message', $options ) ) { echo '<div class="error">' . osintegration_getOption( 'error_message', $options ) . '</div>'; } ?>
	<form method="post" action="options.php" >
		<?php settings_fields( 'osintegration_plugin_options' ); ?>
		<div id="tabs">
			<ul>
				<li><a href="#fragment-0"><span><?php _e( 'Current Images' );?></span></a></li>
				<li><a href="#fragment-1"><span><?php _e( 'General' );?></span></a></li>
				<li><a href="#fragment-2"><span><?php _e( 'Fav Icon' );?></span></a></li>
				<li><a href="#fragment-3"><span><?php _e( 'Progress Web App' );?></span></a></li>
				<li><a href="#fragment-4"><span><?php _e( 'Windows' );?></span></a></li>
				<li><a href="#fragment-5"><span><?php _e( 'iOS' );?></span></a></li>
				<li><a href="#fragment-6"><span><?php _e( 'Advanced' );?></span></a></li>
				<li><a href="#fragment-7"><span><?php _e( 'About' );?></span></a></li>
			</ul>

			<div id="fragment-0">
				<h2>ICO</h2>
				<hr>
				<img src="<?php echo osintegration_getOption( 'img_square_16', $options, '' );?>">
				<br>
				<br>

				<h2>Fav Icons</h2>
				<hr>
				<img src="<?php echo osintegration_getOption( 'img_square_150', $options, '' );?>">
				<br>
				<br>

				<h2>Live Tiles</h2>
				<hr>
				<img src="<?php echo osintegration_getOption( 'img_square_150', $options, '' );?>">
				<br>
				<br>
				<img src="<?php echo osintegration_getOption( 'img_wide_310', $options, '' );?>">
				<br>
				<br>

				<h2>iOS Icons</h2>
				<hr>
				<img src="<?php echo osintegration_getOption( 'ios_icon_144', $options, '' );?>">
				<br>
				<br>

				<h2>iOS Web App</h2>
				<hr>
				<img src="<?php echo osintegration_getOption( 'ios_web_app_460', $options, '' );?>">
				<br>
				<br>

			</div>

			<div id="fragment-1">
				<table class="form-table">
					<tr>
						<th scope="row">Square Image<br/></th>
						<td>
							<input type="url" id="squareimgurl" name="osintegration_options[squareimgurl]" value="<?php echo osintegration_getOption( 'squareimgurl', $options, '' ); ?>" size="100"/>
							<br>
							<input type="button" class="button" name="square_img_button" id="square_img_button" value="Select Image" />
							<br><br>
							<em>This image will be cropped and resized for the various image sizes, you must use a PNG image larger than 450x450 px.</em>
						</td>
						<td rowspan="2" width="150px" align="center" valign="top">
							<h2>Preview</h2>
							<hr>
							<div id="osintbgpreview" style="background-color: <?php echo osintegration_getOption( 'background-color', $options, "" ); ?>; width: 150px; height: 150px;">
								<img id="osintimgpreview" width="150px" height="150px" src="<?php echo osintegration_getOption( 'squareimgurl', $options, '' ); ?>">
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">Wide Image<br/></th>
						<td>
							<input type="url" id="wideimgurl" name="osintegration_options[wideimgurl]" value="<?php echo osintegration_getOption( 'wideimgurl', $options, '' ); ?>" size="100"/>
							<br>
							<input type="button" class="button" name="wide_img_button" id="wide_img_button" value="Select Image" />
							<br><br>
							<em>This image will be cropped and resized for the various image sizes, you must use a PNG image larger than 450x218 px.</em>
						</td>
					</tr>
					<tr>
						<th scope="row">Background Color</th>
						<td colspan="2">
							<input type="text" id="color" class="color-field" name="osintegration_options[background-color]" value="<?php echo osintegration_getOption( 'background-color', $options, '' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">Site Title</th>
						<td colspan="2">
							<input type="text" id="title" name="osintegration_options[title]" value="<?php echo osintegration_getOption( 'title', $options, '' ); ?>" />
							<br><br>
							<em>This will be used in Windows Live Tiles and iOS instead of the default WordPress site title.</em>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<p class="submit">
								<input type="submit" class="button-primary" value="Save Changes" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-2">
				<table class="form-table">
					<tr>
						<th scope="row">Enable Fav Icon</th>
						<td>
							<input type="checkbox" id="enablefavicon" name="osintegration_options[enablefavicon]"<?php if( osintegration_getOption( 'enablefavicon', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Fav Icon Path</th>
						<td>
							<input type="text" id="faviconpath" name="osintegration_options[faviconpath]" value="<?php if( osintegration_getOption( 'faviconpath', $options ) ) { echo osintegration_getOption( 'faviconpath', $options, '' ); } else { echo str_replace( '\\', '/', ABSPATH ); } ?>" size="50"/>
						</td>
					</tr>
					<tr>
						<th scope="row">Include 64px Image</th>
						<td>
							<input type="checkbox" id="favicon64" name="osintegration_options[favicon64]"<?php if( osintegration_getOption( 'favicon64', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Include 96px Image</th>
						<td>
							<input type="checkbox" id="favicon96" name="osintegration_options[favicon96]"<?php if( osintegration_getOption( 'favicon96', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" class="button-primary" value="Save Changes" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-3">
				<table class="form-table">
					<tr>
						<th scope="row">Enable Progressive Web App</th>
						<td>
							<input type="checkbox" id="enablepwa" name="osintegration_options[enablepwa]"<?php if( osintegration_getOption( 'enablepwa', $options ) ) { echo " CHECKED"; } ?>/>
							<?php
								if( strtolower( substr( get_bloginfo( 'url' ), 8 ) ) !== 'https://' ) {
							?>
							<br>
							<br>
							<i>NOTE: Progressive Web Apps require your site to be delivered over https, you can still enable PWA if you do not have https enabled, but the clients will not see the "Add to home screen" option in their browser.</i>
							<?php
								}
							?>
						</td>
					</tr>
					<tr>
						<th scope="row">PWA Short Name</th>
						<td>
							<input type="text" id="pwashortname" name="osintegration_options[pwashortname]" value="<?php if( osintegration_getOption( 'pwashortname', $options ) ) { echo osintegration_getOption( 'pwashortname', $options, '' ); } else { echo substr( str_replace( ' ', '', get_bloginfo( 'name' ) ), 0, 12 ); } ?>" size="20"/>
						</td>
					</tr>
					<tr>
						<th scope="row">PWA Name</th>
						<td>
							<input type="text" id="pwaname" name="osintegration_options[pwaname]" value="<?php if( osintegration_getOption( 'pwaname', $options ) ) { echo osintegration_getOption( 'pwaname', $options, '' ); } else { echo get_bloginfo( 'name' ); } ?>" size="50"/>
						</td>
					</tr>
					<tr>
						<th scope="row">PWA Landing URL</th>
						<td>
							<input type="url" id="pwalandingurl" name="osintegration_options[pwalandingurl]" value="<?php if( ! isset( $options['pwalandingurl'] ) ) { echo get_bloginfo( 'url' ); } else { echo $options['pwalandingurl']; } ?>" size="50" />
						</td>
					</tr>
					<tr>
						<th scope="row">Hide browser controls</th>
						<td>
							<input type="checkbox" id="pwahidebrowser" name="osintegration_options[pwahidebrowser]"<?php if( osintegration_getOption( 'pwahidebrowser', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Landscape mode</th>
						<td>
							<input type="checkbox" id="pwalandscape" name="osintegration_options[pwalandscape]"<?php if( osintegration_getOption( 'pwalandscape', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Theme Color</th>
						<td colspan="2">
							<input type="text" id="pwathemecolor" class="color-field" name="osintegration_options[pwathemecolor]" value="<?php echo osintegration_getOption( 'pwathemecolor', $options, '' ); ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<p class="submit">
								<input type="submit" class="button-primary" value="Save Changes" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-4">
				<table class="form-table">
					<tr>
						<th scope="row">Enable Live Tile</th>
						<td>
							<input type="checkbox" id="enablelivetile" name="osintegration_options[enablelivetile]"<?php if( osintegration_getOption( 'enablelivetile', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">RSS Feed URL</th>
						<td>
							<input type="url" id="rssurl" name="osintegration_options[rssurl]" value="<?php if( ! isset( $options['rssurl'] ) ) { echo  get_bloginfo( 'rss2_url' ); } else { echo $options['rssurl']; } ?>" size="50" />
						</td>
					</tr>
					<tr>
						<th>Update Interval</th>
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
						<th scope="row" colspan=2><h2>Local XML File</h2></th>
					</tr>
					<tr>
						<th scope="row">Enable Local XML</th>
						<td>
							<input type="checkbox" id="localxml" name="osintegration_options[localxml]"<?php if( osintegration_getOption( 'localxml', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Include featured image</th>
						<td>
							<input type="checkbox" id="localfimage" name="osintegration_options[localfimage]"<?php if( osintegration_getOption( 'localfimage', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Search body for featured image</th>
						<td>
							<input type="checkbox" id="searchbody" name="osintegration_options[searchbody]"<?php if( osintegration_getOption( 'searchbody', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Use square image if no image found</th>
						<td>
							<input type="checkbox" id="xmldefaultimage" name="osintegration_options[xmldefaultimage]"<?php if( osintegration_getOption( 'xmldefaultimage', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" class="button-primary" value="Save Changes" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-5">
				<table class="form-table">
					<tr>
						<th scope="row">Enable iOS Support</th>
						<td>
							<input type="checkbox" id="enableios" name="osintegration_options[enableios]"<?php if( osintegration_getOption( 'enableios', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Enable Web App Support</th>
						<td>
							<input type="checkbox" id="enablewebapp" name="osintegration_options[enablewebapp]"<?php if( osintegration_getOption( 'enablewebapp', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Enable Link Override</th>
						<td>
							<input type="checkbox" id="enablelinkoverride" name="osintegration_options[enablelinkoverride]"<?php if( osintegration_getOption( 'enablelinkoverride', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Use wide image for web app loading screen</th>
						<td>
							<input type="checkbox" id="widewebapp" name="osintegration_options[widewebapp]"<?php if( osintegration_getOption( 'widewebapp', $options ) ) { echo " CHECKED"; } ?>/>
						</td>
					</tr>
					<tr>
						<th scope="row">Web App Logo Location</th>
						<td>
							<?php $options['logo-position'] = osintegration_getOption( 'logo-position', $options, 1 ); ?>
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
						<th scope="row">Web App Status Bar Style</th>
						<td>
							<select name="osintegration_options[statusbarstyle]">
								<?php $options['statusbarstyle'] = osintegration_getOption( 'statusbarstyle', $options, 2 ); ?>
								<option value="2" <?php selected( $options['statusbarstyle'], 2 ); ?>>Default</option>
								<option value="1" <?php selected( $options['statusbarstyle'], 1 ); ?>>Black</option>
								<option value="0" <?php selected( $options['statusbarstyle'], 0 ); ?>>Translucent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" class="button-primary" value="Save Changes" />
							</p>
						</td>
					</tr>
				</table>
			</div>

			<div id="fragment-6">
				<table class="form-table">
<?php GLOBAL $wp_version; if( version_compare( $wp_version, '4.2.99', '>' ) ) { ?>
				<tr>
						<th scope="row">Allow WordPress Site Icon</th>
						<td>
							<input type="checkbox" id="wpsiteiconmeta" name="osintegration_options[wpsiteiconmeta]"<?php if( osintegration_getOption( 'wpsiteiconmeta', $options ) ) { echo " CHECKED"; } ?>/><br>
							<br>
							<i>OS Integration will override WordPress's Site Icon settings and strip the meta information from the page headers.  If you wish to use WordPress's Site Icons, you can override this behaviour by checking this option.</i>
						</td>
					</tr>
<?php } ?>
					<tr>
						<th scope="row">Force rebuild</th>
						<td>
							<input type="checkbox" id="forcerebuild" name="osintegration_options[forcerebuild]"/><br>
							<br>
							<i>OS Integration only builds new icons/images when the selected square/wide images change, this option will force a one time rebuild of everything when you select "Save Changes".</i>
						</td>
					</tr>
					<tr>
						<td colspan=2>
							<h2>Override individual image files to be used</h2>
						</td>
					</tr>
<?php
foreach( $options as $key => $option )
	{
	if( substr( $key, 0, 4 ) == 'img_' || substr( $key, 0, 4) == 'ios_' )
		{
?>
					<tr>
						<th scope="row"><?php echo $key; ?>:</th>
						<td>
							<input type="url" id="adv_<?php echo $key;?>" name="osintegration_options[adv_<?php echo $key;?>]" value="<?php echo osintegration_getOption( 'adv_' . $key, $options, '' );?>" size="100"/>
						</td>
					</tr>
<?php
		}
	}
?>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" class="button-primary" value="Save Changes" />
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
							<td scope="row" align="center"><h2><?php echo sprintf( __( 'OS Integration V%s' ), OSINTVER ); ?></h2></td>
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
									<li><?php echo sprintf( __('Have you read the %s?' ), '<a title="' . __('FAQs') . '" href="http://os-integration.com/?page_id=19" target="_blank">' . __('FAQs'). '</a>');?></li>
									<li><?php echo sprintf( __('Have you search the %s for a similar issue?' ), '<a href="http://wordpress.org/support/plugin/os-integration" target="_blank">' . __('support forum') . '</a>');?></li>
									<li><?php _e('Have you search the Internet for any error messages you are receiving?' );?></li>
									<li><?php _e('Make sure you have access to your PHP error logs.' );?></li>
								</ul>

								<p><?php _e('And a few things to double-check:' );?></p>

								<ul style="list-style-type: disc; list-style-position: inside; padding-left: 25px;">
									<li><?php _e('Have you double checked the plugin settings?' );?></li>
									<li><?php _e('Do you have all the required PHP extensions installed?' );?></li>
									<li><?php _e('Are you getting a blank or incomplete page displayed in your browser?  Did you view the source for the page and check for any fatal errors?' );?></li>
									<li><?php _e('Have you checked your PHP and web server error logs?' );?></li>
								</ul>

								<p><?php _e('Still not having any luck?' );?> <?php echo sprintf(__('Then please open a new thread on the %s.' ), '<a href="http://wordpress.org/support/plugin/os-integration" target="_blank">' . __('WordPress.org support forum') . '</a>');?></p>
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
