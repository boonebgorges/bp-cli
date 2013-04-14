<?php

class BPCLI_Group extends BPCLI_Component {
	public function __construct( $args = array() ) {
		if ( empty( $args[0] ) ) {
			WP_CLI::error( 'NO DUDE' );
		}
	}
}
