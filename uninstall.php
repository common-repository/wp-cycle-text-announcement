<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('wpcytxt_title');
 
// for site options in Multisite
delete_site_option('wpcytxt_title');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cycletext_settings");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cycletext_content");