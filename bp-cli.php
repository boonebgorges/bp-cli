<?php
/*
Plugin Name: BP-CLI
Version: 0.1-alpha
Description: BuddyPress commands for use with wp-cli
Author: Boone B Gorges
Author URI: http://boone.gorg.es
Plugin URI: http://github.com/boonebgorges/bp-cli
Text Domain: bp-cli
Domain Path: /languages
*/

function bpcli_init() {
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		$idir = __DIR__ . '/includes/';
		require( $idir . 'bp.php' );
		require( $idir . 'component.php' );

		$cdir = $idir . '/components';
		if ( $h = opendir( $cdir ) ) {
			while ( false !== ( $file = readdir( $h ) ) ) {
				if ( 0 === strpos( $file, '.' ) ) {
					continue;
				}

				include( $cdir . '/' . $file );
			}
			closedir( $h );
		}
	}
}
add_action( 'bp_include', 'bpcli_init' );
