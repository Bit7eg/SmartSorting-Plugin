<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 */

// If uninstall not called from WordPress, then die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

$option_list = array(
    'ss_views_delay',
);
foreach ( $option_list as $option ) {
    delete_option( $option );
    delete_site_option( $option );
}

$meta_list = array(
    'spv_views',
    'spv_sales',
    'spv',
);
foreach ( $meta_list as $meta ) {
    delete_metadata( 'post', null, $meta, '', true );
}

global $wpdb;
$table_list = array(
    '`wp_smart-sorting_views_table`',
);
foreach ( $table_list as $table ) {
    $wpdb->query(
        $wpdb->prepare(
            "DROP TABLE IF EXISTS %s",
            $table
        )
    );
}

