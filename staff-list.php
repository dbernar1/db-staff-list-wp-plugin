<?php
/*
Plugin Name: Staff List
Plugin URI: https://github.com/dbernar1/db-staff-list-wp-plugin
Description: Enables creating a staff directory on your WordPress website
Version: 0.1
Author: Dan Bernardic
Author URI: http://gravatar.com/dbernar1
License: GPL2
*/

define( 'DB_SL_POST_TYPE', 'db_sl_team_member' );

register_activation_hook( __FILE__, 'db_sl_flush_rewrite_rules' );

function db_sl_flush_rewrite_rules() {
	create_team_member_content_type();
	flush_rewrite_rules();
}

add_action( 'init', 'create_team_member_content_type' );

function create_team_member_content_type() {
  $labels = array(
    'name' => 'Team Members',
    'singular_name' => 'Team Member',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Team Member',
    'edit_item' => 'Edit Team Member',
    'new_item' => 'New Team Member',
    'all_items' => 'All Team Members',
    'view_item' => 'View Team Member',
    'search_items' => 'Search Team Members',
    'not_found' =>  'No team members found',
    'not_found_in_trash' => 'No team members found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Team Members'
  );

  $post_type_config = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'team' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'supports' => array( 'title', 'editor', 'thumbnail' )
  );

	register_post_type( DB_SL_POST_TYPE, $post_type_config );


}

add_action( 'admin_init', 'db_sl_additional_fields_for_team_member' );

function db_sl_additional_fields_for_team_member() {
    // Check if plugin is activated or included in theme
    if ( !class_exists( 'RW_Meta_Box' ) ) return;

    $prefix_for_field_ids = 'db_sl_';

    $additional_fields_config = array(
        'id'       => 'additional',
        'title'    => 'Additional Information',
        'pages'    => array( DB_SL_POST_TYPE ),
        'context'  => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name'  => 'Position Title',
                'id'    => $prefix_for_field_ids . 'title',
                'type'  => 'text',
            ),
            array(
                'name'  => 'Email',
                'id'    => $prefix_for_field_ids . 'email',
                'type'  => 'text',
            ),
            array(
                'name'  => 'Twitter Handle',
                'id'    => $prefix_for_field_ids . 'twitter',
                'type'  => 'text',
            ),
        )
    );

    new RW_Meta_Box( $additional_fields_config );
}
