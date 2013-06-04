<?php

/**
 * 'bp' command
 *
 * We fake multi-tiered commands by using the first arg as a router. Eg
 *
 *   wp bp group create
 *
 * calls the 'group' method here, and routs it to the BPCLI_Group class for
 * processing
 *
 * @package bp-cli
 * @since 1.0
 */
class BPCLI_BP_Command extends WP_CLI_Command {

	/**
	 * List groups
	 * Also, foo
	 */
	public function group( $args, $assoc_args ) {
		if ( ! bp_is_active( 'groups' ) ) {
			WP_CLI::error( 'The Groups component is not active.' );
		}

		$g = new BPCLI_Group( $args, $assoc_args );
		// FOO!
		$g->run();
	}
}
WP_CLI::add_command( 'bp', 'BPCLI_BP_Command' );
