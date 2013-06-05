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
			groups_update_groupmeta( $id, 'total_member_count', 1 );
			$group = groups_get_group( array( 'group_id' => $id ) );
			WP_CLI::success( 'Group $id created: ' . bp_get_group_permalink( $group ) );
		} else {
			WP_CLI::error( 'Could not create group.' );
		}
	}

	/**
	 * Add a member to a group.
	 *
	 * @synopsis --group=<group> --user=<user>
	 */
	public function add_member() {
		$r = wp_parse_args( $this->assoc_args, array(
			'group_id' => null,
			'user_id' => null,
			'role' => 'member',
		) );

		if ( empty( $r['group_id'] ) || empty( $r['user_id'] ) ) {
			WP_CLI::error( 'You must provide --group_id and --user_id parameters when adding a member to a group.' );
		}

		// Convert --group_id to group ID
		// @todo this'll be screwed up if the group has a numeric slug
		if ( ! is_numeric( $r['group_id'] ) ) {
			$group_id = groups_get_id( $r['group_id'] );
		} else {
			$group_id = $r['group_id'];
		}

		// Check that group exists
		$group_obj = groups_get_group( array( 'group_id' => $group_id ) );
		if ( empty( $group_obj->id ) ) {
			WP_CLI::error( 'No group found by that slug or id.' );
		}

		// Convert --user_id to user ID
		// @todo this'll be screwed up if user has a numeric user_login
		// @todo Have to use user_id because WP_CLI hijocks --user
		if ( ! is_numeric( $r['user_id'] ) ) {
			$user_id = (int) username_exists( $r['user_id'] );
		} else {
			$user_id = $r['user_id'];
			$user_obj = new WP_User( $user_id );
			$user_id = $user_obj->ID;
		}

		if ( empty( $user_id ) ) {
			WP_CLI::error( 'No user found by that username or id' );
		}

		// Sanitize role
		if ( ! in_array( $r['role'], array( 'member', 'mod', 'admin' ) ) ) {
			$r['role'] = 'member';
		}

		$joined = groups_join_group( $group_id, $user_id );

		if ( $joined ) {
			if ( 'member' !== $r['role'] ) {
				$the_member = new BP_Groups_Member( $user_id, $group_id );
				$member->promote( $r['role'] );
			}

			$success = sprintf(
				'Added user #%d (%s) to group #%d (%s) as %s',
				$user_id,
				$user_obj->user_login,
				$group_id,
				$group_obj->name,
				$r['role']
			);
			WP_CLI::success( $success );
		} else {
			WP_CLI::error( 'Could not add user to group.' );
		}
	}
}
