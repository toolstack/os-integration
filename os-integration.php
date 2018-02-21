<?php
/*
Plugin Name: OS Integration
Description: Integrate your site in to your users OS, Windows Live Tiles, icons for Apple and Android, iOS Web App!
Version: 2.1
Plugin URI: http://toolstack.com/os-integration
Author: Greg Ross
Author URI: http://toolstack.com
Tags: Windows 8, Internet Explorer, IE10, IE11, live tile, RSS, App, tiles, start screen, pinned site, branding, favicon, apple, icons, Android, Windows Phone 8.1, WebApp, web app
License: GPL

Compatible with WordPress 3.5+.

Read the accompanying readme.txt file for instructions and documentation.

Copyright (c) 2014-18 by Greg Ross

This software is released under the GPL v2.0, see license.txt for details
*/

/*
****************************************
Plugin Variables and Defines Starts Here
****************************************
*/

// Define the plugin version.
DEFINE( 'OSINTVER', '2.1' );

// Define the name of the WordPress option to use.
DEFINE( 'OSINTOPTIONNAME', 'osintegration_options' );

/*
***************************
Plugin Includes Starts Here
***************************
*/

// Include the widget code.
include_once dirname( __FILE__ ) . '/widget.php';

// Include the page options code.
include_once( 'includes/page-options.php' );

// Include the options validation and image generation code.
include_once( 'includes/validate-options.php');

// Include the help screens for the settings page.
include_once( 'includes/help-options.php' );

// Include the output generator code.
include_once( 'includes/output-generator.php' );

/*
****************************
Plugin Functions Starts Here
****************************
*/

// Delete options table entries ONLY when plugin deactivated AND deleted.
function osintegration_delete_plugin_options()
	{
	delete_option( OSINTOPTIONNAME );
	}

// Display a Settings link on the main plugins page.
function osintegration_plugin_action_links( $links, $file )
	{
	if ( $file == plugin_basename( __FILE__ ) )
		{
		$osintegration_links = '<a href="' . get_admin_url() . 'options-general.php?page=os-integration%2Fos-integration.php">' . __( 'Settings' ) . '</a>';

		// Add our settings to the top of the list.
		array_unshift( $links, $osintegration_links );
		}

	return $links;
	}

// Define default option settings, called when the plugin is activated.
function osintegration_add_defaults()
	{
	// Check to see if we already have options set.
	$tmp = get_option( OSINTOPTIONNAME );

    if( !is_array( $tmp ) )
		{
		delete_option( OSINTOPTIONNAME );

		$arr = array(
					'plugin_version' 			=> OSINTVER,
					'notification_frequency' 	=> 360,
					'background-color' 			=> '#111111',
					'title' 					=> get_bloginfo( 'name' ),
					'enablefavicon'				=> 'on',
					'enablelivetile'			=> 'on',
					'enableios'					=> 'on',
					'rssurl'					=> get_bloginfo( 'rss2_url' ),
					'localfimage'				=> 'on',
					'searchbody'				=> 'on',
					'xmldefaultimage'			=> 'on',
		);

		update_option( OSINTOPTIONNAME, $arr );

		add_feed( 'msxmllivetile', 'osintegration_outputxmlfeed' );

		//Ensure the $wp_rewrite global is loaded
		global $wp_rewrite;
		//Call flush_rules() as a method of the $wp_rewrite object
		$wp_rewrite->flush_rules( false );
		}
	}

// Init plugin options to white list our options.
function osintegration_init()
	{
	register_setting( 'osintegration_plugin_options', OSINTOPTIONNAME, 'osintegration_validate_options' );
	}

// Add us to the settings menu.
function osintegration_add_options_page()
	{
	$page = add_options_page( 'OS Integration Settings', 'OS Integration', 'manage_options', __FILE__, 'osintegration_options_page' );
	add_action( 'load-' . $page, 'osintegration_create_help_screen' );
	}

// Prepare the media uploader and our admin scripts.
function osintegration_admin_scripts()
	{
	// Must be running 3.5+ to use color pickers and image upload.
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'osintegration-admin', plugins_url( "/js/os-integration.js", __FILE__ ), array( 'wp-color-picker', 'jquery' ) );

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');

	global $wp_scripts;
	wp_register_style("jquery-ui-css", plugin_dir_url(__FILE__) . "css/jquery-ui-1.10.4.custom.css");
	wp_enqueue_style("jquery-ui-css");
	wp_register_style("jquery-ui-tabs-css", plugin_dir_url(__FILE__) . "css/jquery-ui-tabs.css");
	wp_enqueue_style("jquery-ui-tabs-css");
	}

// Get a option value.
function osintegration_getoption( $option, $options = null, $default = false )
	{
	if( $options == null ) { $options = get_option( OSINTOPTIONNAME ); }

	if( array_key_exists( $option, $options ) )
		{
		return $options[$option];
		}
	else
		{
		return $default;
		}
	}

// WordPress 4.3 introduces the site icon feature, since we're doing all that work, this function removes
// WP's output of site icon meta info from the page header.
function osintegration_site_icon_meta_tags_filter( $meta_tags )
	{
	return array();
	}

// This function adds the rss feed for the MS Live Tile support and disables WP 4.3's site icon support.
function osintegration_wpinit()
	{
	// Get the current plugin options;
	$options = get_option( OSINTOPTIONNAME );

	// If the Windows Live Tile is enabled and we're using a local XML feed, add it to WordPress.
	if( array_key_exists( 'localxml', $options ) && $options['localxml'] && array_key_exists( 'enablelivetile', $options ) && $options['enablelivetile'] )
		{
		add_feed( 'mslivetile', 'osintegration_outputxmlfeed' );
		}

	// If we're running WordPress 4.3 or above, disable the Site Icon meta data because we're going to generate our own.
	GLOBAL $wp_version;

	if( ! array_key_exists( 'wpsiteiconmeta', $options ) ) { $options['wpsiteiconmeta'] = false; }
	if( version_compare( $wp_version, '4.2.99', '>' ) && array_key_exists( 'wpsiteiconmeta', $options ) && ! $options['wpsiteiconmeta'] && array_key_exists( 'squareimgurl', $options ) && $options['squareimgurl'] )
		{
		add_filter( 'site_icon_meta_tags', 'osintegration_site_icon_meta_tags_filter', 99, 1);
		}
	}

/*
***********************
Plugin Code Starts Here
***********************
*/

// Set-up Action and Filter Hooks

// Runs osintegartion_add_defaults() each time the plugin is activated.
register_activation_hook( __FILE__, 'osintegration_add_defaults' );

// Runs osintegartion_delete_plugin_options() when the plugin uninstalled.
register_uninstall_hook( __FILE__, 'osintegration_delete_plugin_options' );

// Registers the settings with WordPress.
add_action( 'admin_init', 'osintegration_init' );

// Adds the options page to the settings menu.
add_action( 'admin_menu', 'osintegration_add_options_page' );

// Adds a link to our settings in the plugin list.
add_filter( 'plugin_action_links', 'osintegration_plugin_action_links', 10, 2 );

if( isset( $_GET['page']) && $_GET['page'] == 'os-integration/os-integration.php' )
	{
	// Load the JavaScript for the options page.
	add_action( 'admin_enqueue_scripts', 'osintegration_admin_scripts' );
	}

// Setup the header and init hooks for the plugin.
add_action( 'wp_head', 'osintegration_output' );
add_action( 'admin_head', 'osintegration_output' );
add_action( 'init', 'osintegration_wpinit' );