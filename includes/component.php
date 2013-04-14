<?php

class BPCLI_Component {
	protected $subcommand;
	protected $assoc_args;

	public function __construct( $args = array(), $assoc_args = array() ) {
		if ( empty( $args[0] ) || ! method_exists( $this, $args[0] ) ) {
			WP_CLI::error( 'That is not a valid command.' );
		}

		$this->subcommand = $args[0];
		$this->assoc_args = $assoc_args;
	}

	public function run() {
		call_user_func( array( $this, $this->subcommand ), $this->assoc_args );
	}
}
