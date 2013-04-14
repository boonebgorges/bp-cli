<?php

class BPCLI_Group extends BPCLI_Component {
	/**
	 * Create a group.
	 *
	 * @synopsis --name=<name> [--slug=<slug>] [--description=<description>] [--creator_id==<creator_id>] [--status=><status>] [--enable_forum=<enable_forum>] [--date_created'=<date_created>]
	 */
	public function create() {
		$r = wp_parse_args( $this->assoc_args, array(
			'name' => '',
			'slug' => '',
			'description' => '',
			'creator_id' => bp_loggedin_user_id(),
			'status' => 'public',
			'enable_forum' => 1,
			'date_created' => bp_core_current_time(),
		) );

		if ( ! $r['name'] ) {
			WP_CLI::error( 'You must provide a --name parameter when creating a group.' );
		}

		// Auto-generate some stuff
		if ( ! $r['slug'] ) {
			$r['slug'] = groups_check_slug( sanitize_title( $r['name'] ) );
		}

		if ( ! $r['description'] ) {
			$r['description'] = sprintf( 'Description for group "%s"', $r['name'] );
		}

		if ( $id = groups_create_group( $r ) ) {
			$group = groups_get_group( array( 'group_id' => $id ) );
			WP_CLI::success( 'Group $id created: ' . bp_get_group_permalink( $group ) );
		} else {
			WP_CLI::error( 'Could not create group.' );
		}
	}
}
