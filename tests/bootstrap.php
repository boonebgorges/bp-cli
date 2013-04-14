<?php

if ( ! defined( 'BP_TESTS_DIR' ) ) {
	define( 'BP_TESTS_DIR', __DIR__ . '/../../buddypress/tests' );
}

if ( file_exists( BP_TESTS_DIR . '/bootstrap.php' ) ) {
	require_once getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php';

	function _bootstrap_plugins() {
		require BP_TESTS_DIR . '/includes/loader.php';
		require __DIR__ . '/../bp-cli.php';
	}
	tests_add_filter( 'muplugins_loaded', '_bootstrap_plugins' );

	require getenv( 'WP_TESTS_DIR' ) . '/includes/bootstrap.php';

	require BP_TESTS_DIR . '/includes/testcase.php';

	require __DIR__ . '/bp-cli-testcase.php';
}


