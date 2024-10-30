<?php

/*
Plugin Name: HTTP Flood
Plugin URI: https://wordpress.org/plugins/http-flood/
Description: HTTP Flood prevents your system against attacks like HTTP Flood, Land Flood, Form Spoofing, Brute Force, Remote Site Scanners and many more on similar types. It was tested under limitless thread and distributed sources.
Version: 1.0-1
Author: Aydın Antmen
Author URI: https://www.ofis46.com
License: GNU
*/

# Run install function for install database table when plugin activation.
register_activation_hook(__FILE__, 'httpflood_install');
# Run uninstall function for uninstall database table when plugin delete.
register_uninstall_hook(__FILE__, 'httpflood_uninstall');
# Run HTTP Flood on system init.
add_action('init', "httpflood");

/**
 * Check for potential attacks.
 *
 * @access public
 * @version 1.0-1
 * @uses session_start function
 * @uses session_id function
 * @uses file_put_contents function
 * @uses time function
 * @uses $_SERVER super global
 * @uses ARRAY_A constant
 * @uses FILE_APPEND constant
*/
function httpflood() {
    # Make global wordpress db class
    global $wpdb;
    # Get current db prefix
    $table_name = $wpdb->prefix . 'httpflood';
    # Get user ip address
    if (!$ip = $_SERVER['REMOTE_ADDR']) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    # Start session to control users
    session_start();
    # User can start an Land Flood, Brute Force or Form Spoofing attack after some while.
    # So we have to take them under control. Delete current session record if it is out of control.
    $wpdb->query("DELETE FROM $table_name WHERE first+30 < ".time()."");
    # Insert new session or update if it is exists.
    $wpdb->query("INSERT INTO $table_name SET id = '" . session_id() . "', ip = '" . $ip . "', first = '" . time() . "', last = '" . time() . "', visit = '1' ON DUPLICATE KEY UPDATE visit = visit + 1, last = '" . time() . "'");
    # First control against Land Flood, Brute Force and Form Spoofing.
    # These are NOT distributed attacks. So they are collected in a session.
    # ip = '" . $ip . "' is deprecated for catch other parts of a land flood.
    $count = $wpdb->get_results("SELECT DISTINCT ip FROM $table_name WHERE visit > '10' AND last - first < '10'", ARRAY_A);
    # If we catch...
    if (!empty($count) && $count > 0) {
        # Get all records.
        foreach ((array) $count as $row) {
            # Write ip address to .htaccess file.
            # This is a system file and bans ip address on firewall layer when we write the rule.
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/.htaccess',
                PHP_EOL . PHP_EOL . '# Land Flood blocked by HTTP Flood v-1.0;' .
                PHP_EOL . '# On: '.time() . ';'.
                PHP_EOL . 'deny from ' . $row['ip'], FILE_APPEND
            );
        }
    }
    # This one is some complicated. Distributed attacks have different ip addresses and session keys.
    # But we can not comparise attackers with site visitors. This algorithm written after read millions of line attack records.
    $count = $wpdb->get_results("SELECT DISTINCT ip, COUNT(ip) as attacker, MAX(last) - MIN(first) as timing FROM $table_name WHERE ip = '" . $ip . "' AND visit = '1' AND last - first < '10' HAVING attacker > '10' AND timing > '10'", ARRAY_A);
    # If we catch...
    if (!empty($count) && $count > 0) {
        # Get all records.
        foreach ((array) $count as $row) {
            # Write ip address to .htaccess file.
            # This is a system file and bans ip address on firewall layer when we write the rule.
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/.htaccess',
                PHP_EOL . PHP_EOL . '# HTTP Flood blocked by HTTP Flood v-1.0;' .
                PHP_EOL . '# On: '.time() . ';'.
                PHP_EOL . 'deny from ' . $row['ip'], FILE_APPEND
            );
        }
    }
}

function httpflood_install() {
    # Make global wordpress db class
	global $wpdb;
    # HTTP Flood addon requires a worker .htaccess file.
    if (@ file_exists($_SERVER['DOCUMENT_ROOT'] . '/.htaccess')) {
        # Get current db prefix
    	$table_name = $wpdb->prefix . 'httpflood';
        # Get current db charset
    	$charset_collate = $wpdb->get_charset_collate();
        # This addon is standalone version of HTTP Flood.
        # So we need only session id, ip address, first visit time, last visit time and visit count.
    	$sql = "CREATE TABLE $table_name (
          id varchar(32) NOT NULL PRIMARY KEY,
          ip varchar(15) NOT NULL,
          first varchar(10) NOT NULL,
          last varchar(10) NOT NULL,
          visit int(3) NOT NULL
    	) $charset_collate;";
        # Install table.
    	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    	dbDelta( $sql );
    } else {
        # HTTP Flood addon requires a worker .htaccess file.
        wp_die('HTTP Flood requires a writable .htaccess file.');
    }
}

function httpflood_uninstall() {
    # Make global wordpress db class
	global $wpdb;
    # Get current db prefix
	$table_name = $wpdb->prefix . 'httpflood';
    # Drop HTTP Flood table from database
	$sql = "DROP TABLE $table_name;";
    # Run query
	$wpdb->query($sql);
}