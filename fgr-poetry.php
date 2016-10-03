<?php
/**
 * Plugin Name: FGR Poetry
 * Version: 0.2
 * Description: Add support to publish poems
 * Author: Raúl Acuña
 * Author URI: https://www.reacuna.me
 * Plugin URI: https://www.reacuna.me/poetry-plugin
 * Text Domain: fgr-poetry
 * Domain Path: /languages
 * @package FGR Poetry
 */

function generate_post_type($name, $slug, $icon, $labels) {
	register_post_type($name, array(
		'labels' =>$labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor', 'page-attributes', 'custom-fields', 'post-formats' ),
		'has_archive'       => true,
		'rewrite'           => array( 'slug' => $slug ),
		'query_var'         => true,
		'menu_icon'         => $icon,
		'show_in_menu'      => 'edit.php?post_type=page',
		'menu-position'     => 10,
		'show_in_rest'      => true,
		'rest_base'         => $name,
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	));
}

function poem_init() {
	load_plugin_textdomain( 'fgr-poetry', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	$poem_labels = array(
		'name'                => __( 'Poems', 'fgr-poetry' ),
		'singular_name'       => __( 'Poem', 'fgr-poetry' ),
		'all_items'           => __( 'All Poems', 'fgr-poetry' ),
		'new_item'            => __( 'New poem', 'fgr-poetry' ),
		'add_new'             => __( 'Add New', 'fgr-poetry' ),
		'add_new_item'        => __( 'Add New poem', 'fgr-poetry' ),
		'edit_item'           => __( 'Edit poem', 'fgr-poetry' ),
		'view_item'           => __( 'View poem', 'fgr-poetry' ),
		'search_items'        => __( 'Search poems', 'fgr-poetry' ),
		'not_found'           => __( 'No poems found', 'fgr-poetry' ),
		'not_found_in_trash'  => __( 'No poems found in trash', 'fgr-poetry' ),
		'parent_item_colon'   => __( 'Parent poem', 'fgr-poetry' ),
		'menu_name'           => __( 'Poems', 'fgr-poetry' ),
	);
	$speech_labels = array(
		'name'                => __( 'Speeches', 'fgr-poetry' ),
		'singular_name'       => __( 'Speech', 'fgr-poetry' ),
		'all_items'           => __( 'All Speeches', 'fgr-poetry' ),
		'new_item'            => __( 'New speech', 'fgr-poetry' ),
		'add_new'             => __( 'Add New', 'fgr-poetry' ),
		'add_new_item'        => __( 'Add New speech', 'fgr-poetry' ),
		'edit_item'           => __( 'Edit speech', 'fgr-poetry' ),
		'view_item'           => __( 'View speech', 'fgr-poetry' ),
		'search_items'        => __( 'Search speeches', 'fgr-poetry' ),
		'not_found'           => __( 'No speeches found', 'fgr-poetry' ),
		'not_found_in_trash'  => __( 'No speeches found in trash', 'fgr-poetry' ),
		'parent_item_colon'   => __( 'Parent speech', 'fgr-poetry' ),
		'menu_name'           => __( 'Speeches', 'fgr-poetry' ),
	);
	$essay_labels = array(
		'name'                => __( 'Essays', 'fgr-poetry' ),
		'singular_name'       => __( 'Essay', 'fgr-poetry' ),
		'all_items'           => __( 'All Essays', 'fgr-poetry' ),
		'new_item'            => __( 'New essay', 'fgr-poetry' ),
		'add_new'             => __( 'Add New', 'fgr-poetry' ),
		'add_new_item'        => __( 'Add New essay', 'fgr-poetry' ),
		'edit_item'           => __( 'Edit essay', 'fgr-poetry' ),
		'view_item'           => __( 'View essay', 'fgr-poetry' ),
		'search_items'        => __( 'Search essays', 'fgr-poetry' ),
		'not_found'           => __( 'No essays found', 'fgr-poetry' ),
		'not_found_in_trash'  => __( 'No essays found in trash', 'fgr-poetry' ),
		'parent_item_colon'   => __( 'Parent essay', 'fgr-poetry' ),
		'menu_name'           => __( 'Essays', 'fgr-poetry' ),
	);
	generate_post_type('poem', __('poems', 'fgr-poetry'),'dashicons-admin-post', $poem_labels);
	generate_post_type('speech', __('speeches', 'fgr-poetry'),'dashicons-book', $speech_labels);
	generate_post_type('essay', __('essays', 'fgr-poetry'),'dashicons-book-alt', $essay_labels);
}
add_action( 'init', 'poem_init' );

function poem_updated_messages( $messages ) {
	$post = get_post();

	$permalink = get_permalink( $post );

	$messages['poem'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Poem updated. <a target="_blank" href="%s">View poem</a>', 'fgr-poetry'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'fgr-poetry'),
		3 => __('Custom field deleted.', 'fgr-poetry'),
		4 => __('Poem updated.', 'fgr-poetry'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Poem restored to revision from %s', 'fgr-poetry'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Poem published. <a href="%s">View poem</a>', 'fgr-poetry'), esc_url( $permalink ) ),
		7 => __('Poem saved.', 'fgr-poetry'),
		8 => sprintf( __('Poem submitted. <a target="_blank" href="%s">Preview poem</a>', 'fgr-poetry'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Poem scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview poem</a>', 'fgr-poetry'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i', 'fgr-poetry' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Poem draft updated. <a target="_blank" href="%s">Preview poem</a>', 'fgr-poetry'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'poem_updated_messages' );
