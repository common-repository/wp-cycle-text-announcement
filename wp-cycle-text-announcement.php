<?php
/*
Plugin Name: Wp cycle text announcement
Plugin URI: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Description: Wp cycle text plugin is to show the text news with cycle jQuery. Display one news at a time and cycle the remaining in the mentioned location.
Author: Gopi Ramasamy
Version: 8.1
Author URI: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/
Tags: Cycle, text, announcement, wordpress, plugin
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-cycle-text-announcement
Domain Path: /languages
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("WP_WPCYTXT_SETTINGS", $wpdb->prefix . "cycletext_settings");
define("WP_WPCYTXT_CONTENT", $wpdb->prefix . "cycletext_content");
define('Wp_wpcytxt_FAV', 'http://www.gopiplus.com/work/2012/04/07/wp-cycle-text-announcement-wordpress-plugin/');

if ( ! defined( 'WP_wpcytxt_BASENAME' ) )
	define( 'WP_wpcytxt_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_wpcytxt_PLUGIN_NAME' ) )
	define( 'WP_wpcytxt_PLUGIN_NAME', trim( dirname( WP_wpcytxt_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_wpcytxt_PLUGIN_URL' ) )
	define( 'WP_wpcytxt_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_wpcytxt_PLUGIN_NAME );
	
if ( ! defined( 'WP_wpcytxt_ADMIN_URL' ) )
	define( 'WP_wpcytxt_ADMIN_URL', site_url( '/wp-admin/options-general.php?page=wp-cycle-text-announcement' ) );

function wpcytxt_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_WPCYTXT_SETTINGS . "'") != WP_WPCYTXT_SETTINGS) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_WPCYTXT_SETTINGS . "` (
			  `wpcytxt_sid` int(11) NOT NULL auto_increment,
			  `wpcytxt_sname` VARCHAR( 10 ) NOT NULL,
			  `wpcytxt_slink` VARCHAR( 10 ) NOT NULL default '_blank',
			  `wpcytxt_sdirection` VARCHAR( 12 ) NOT NULL default 'scrollLeft',
			  `wpcytxt_sspeed` int(11) NOT NULL default '700',
			  `wpcytxt_stimeout` int(11) NOT NULL default '5000',
			  `wpcytxt_srandom` VARCHAR( 3 ) NOT NULL default 'YES',
			  `wpcytxt_sextra` VARCHAR( 100 ) NOT NULL,
			  PRIMARY KEY  (`wpcytxt_sid`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
		$iIns = "INSERT INTO `". WP_WPCYTXT_SETTINGS . "` (`wpcytxt_sname`)"; 
		
		for($i=1; $i<=10; $i++)
		{
			$sSql = $iIns . " VALUES ('SETTING".$i."')";
			$wpdb->query($sSql);
		}
	}
	if($wpdb->get_var("show tables like '". WP_WPCYTXT_CONTENT . "'") != WP_WPCYTXT_CONTENT) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_WPCYTXT_CONTENT . "` (
			  `wpcytxt_cid` int(11) NOT NULL auto_increment,
			  `wpcytxt_ctitle` VARCHAR( 1024 ) NOT NULL,
			  `wpcytxt_clink` VARCHAR( 1024 ) NOT NULL default '#',
			  `wpcytxt_cstartdate` datetime NOT NULL default '2021-01-01 00:00:00',
			  `wpcytxt_cenddate` datetime NOT NULL default '2029-12-30 00:00:00',
			  `wpcytxt_csetting` VARCHAR( 12 ) NOT NULL,
			  PRIMARY KEY  (`wpcytxt_cid`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
		$iIns = "INSERT INTO `". WP_WPCYTXT_CONTENT . "` (`wpcytxt_ctitle`, `wpcytxt_csetting`)"; 
		
		for($i=1; $i<=6; $i++)
		{
			if($i >= 1 and $i<=2) { $j = 1; } elseif ($i >= 3 and $i<=4) { $j = 2; } else { $j = 3; }
			$sSql = $iIns . " VALUES ('Lorem Ipsum is simply dummy text of the printing industry ".$i.".', 'SETTING".$j."')";
			$wpdb->query($sSql);
		}
	}
	add_option('wpcytxt_title', "Announcement");
}

function wpcytxt_admin_options() 
{
	global $wpdb;
	//include_once("content-management.php");
	
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'add':
			include('pages/content-add.php');
			break;
		case 'edit':
			include('pages/content-edit.php');
			break;
		case 'addcycle':
			include('pages/cycle-setting-add.php');
			break;
		case 'editcycle':
			include('pages/cycle-setting-edit.php');
			break;
		case 'showcycle':
			include('pages/cycle-setting-show.php');
			break;
		default:
			include('pages/content-show.php');
			break;
	}
}

function wpcytxt_shortcode( $atts ) 
{
	global $wpdb;

	// [cycle-text setting="SETTING1"]	
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$setting = $atts['setting'];
	
	$wpcycle = "";
	$sSql = "select wpcytxt_sid, wpcytxt_sname, wpcytxt_slink, wpcytxt_sdirection,";
	$sSql = $sSql . " wpcytxt_sspeed, wpcytxt_stimeout, wpcytxt_srandom from ". WP_WPCYTXT_SETTINGS ." where 1=1";
	$sSql = $sSql . " and wpcytxt_sname='".strtoupper($setting)."'";
	$wpcycletxt_settings = $wpdb->get_results($sSql);
	if ( ! empty($wpcycletxt_settings) ) 
	{
			$settings = $wpcycletxt_settings[0];
			$wpcytxt_sname = $settings->wpcytxt_sname; 
			$wpcytxt_slink = $settings->wpcytxt_slink; 
			$wpcytxt_sdirection = $settings->wpcytxt_sdirection; 
			$wpcytxt_sspeed = $settings->wpcytxt_sspeed; 
			$wpcytxt_stimeout = $settings->wpcytxt_stimeout; 
			$wpcytxt_srandom = $settings->wpcytxt_srandom; 
			
			$wpcycle = $wpcycle . '<div id="WP-CYCLE-'.$wpcytxt_sname.'">';
			$sSql = "select wpcytxt_cid, wpcytxt_ctitle, wpcytxt_clink from ". WP_WPCYTXT_CONTENT ." where 1=1";
			$sSql = $sSql . " and (`wpcytxt_cstartdate` <= NOW() and `wpcytxt_cenddate` >= NOW())";
			$sSql = $sSql . " and wpcytxt_csetting='".strtoupper($setting)."'";
			$wpcycletxt = $wpdb->get_results($sSql);
			if ( ! empty($wpcycletxt) ) 
			{
				foreach ( $wpcycletxt as $text ) 
				{
					$wpcytxt_ctitle = stripslashes($text->wpcytxt_ctitle);
					$wpcytxt_clink = $text->wpcytxt_clink;
					$wpcycle = $wpcycle . '<p><a target="' . $wpcytxt_slink . '" href="' . $wpcytxt_clink . '">' . $wpcytxt_ctitle . '</a></p>';
				}
			}
			
			$wpcycle = $wpcycle . '</div>';
			$wpcycle = $wpcycle . '<script type="text/javascript">';
			$wpcycle = $wpcycle . 'jQuery(function() {';
			$wpcycle = $wpcycle . "jQuery('#WP-CYCLE-".strtoupper($setting)."').cycle({fx: '".$wpcytxt_sdirection."',speed: " . $wpcytxt_sspeed . ",timeout: " . $wpcytxt_stimeout . "";
			$wpcycle = $wpcycle . '});';
			$wpcycle = $wpcycle . '});';
			$wpcycle = $wpcycle . '</script>';
	}
	else
	{
		$wpcycle = __('No records found', 'wp-cycle-text-announcement');
	}
	return $wpcycle;
}

function wpcytxt_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page( __('Wp cycle text', 'wp-cycle-text-announcement'), __('Wp cycle text', 'wp-cycle-text-announcement'), 
								'manage_options', 'wp-cycle-text-announcement', 'wpcytxt_admin_options' );
	}
}

function wpcytxt_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jquery.cycle.all.latest', WP_wpcytxt_PLUGIN_URL.'/js/jquery.cycle.all.latest.js');
		wp_enqueue_style( 'wp-cycle-text-announcement', WP_wpcytxt_PLUGIN_URL.'/wp-cycle-text-style.css');
	}	
}

function wpcytxt_deactivation() 
{
	// No action required.
}

function wpcytxt_textdomain() 
{
	  load_plugin_textdomain( 'wp-cycle-text-announcement', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function wpcytxt_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'wp-cycle-text-announcement':
				wp_register_script( 'wp-cycle-adminscripts', WP_wpcytxt_PLUGIN_URL . '/pages/setting.js', '', '', true );
				wp_enqueue_script( 'wp-cycle-adminscripts' );
				$wp_cycle_adminscripts_params = array(
					'wpcytxt_sname'  	=> __( 'Please select the setting name.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_slink'  	=> __( 'Please select the link option.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_sspeed'  	=> __( 'Please enter the slider speed, only number.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_stimeout'  => __( 'Please enter the slider timeout, only number.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_sdirection'=> __( 'Please select the slider direction', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_sdelete'  	=> __( 'Do you want to delete this record?', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_ctitle'  	=> __( 'Please enter the announcement.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_clink'  	=> __( 'Please enter the link, if no link just enter #.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_csetting'  => __( 'Please select the setting.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_cstartdate'=> __( 'Please enter the start date, YYYY-MM-DD.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_cenddate'  => __( 'Please enter the end date, YYYY-MM-DD.', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
					'wpcytxt_cdelete'  	=> __( 'Do you want to delete this record?', 'wp-cycle-select', 'wp-cycle-text-announcement' ),
				);
				wp_localize_script( 'wp-cycle-adminscripts', 'wp_cycle_adminscripts', $wp_cycle_adminscripts_params );
				break;
		}
	}
}

add_action('plugins_loaded', 'wpcytxt_textdomain');
add_shortcode( 'cycle-text', 'wpcytxt_shortcode' );
add_action('admin_menu', 'wpcytxt_add_to_menu');
add_action('wp_enqueue_scripts', 'wpcytxt_add_javascript_files');
register_activation_hook(__FILE__, 'wpcytxt_install');
register_deactivation_hook(__FILE__, 'wpcytxt_deactivation');
add_action('admin_enqueue_scripts', 'wpcytxt_adminscripts');
?>