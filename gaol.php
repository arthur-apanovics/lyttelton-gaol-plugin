<?php
/*
 * @link              likeminded.co.nz
 * @since             1.0.0
 * @package           Gaol
 *
 * @wordpress-plugin
 * Plugin Name:       Lyttelton Gaol
 * Plugin URI:        likeminded.co.nz
 * Description:       Lyttelton gaol convict post type & fields
 * Version:           1.0.0
 * Author:            Like-Minded
 * Author URI:        likeminded.co.nz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gaol
 */

use lyttelton_gaol\fields;

require 'gaol_metaboxes.php';
require 'gaol_importer.php';

// Register Custom Post Type
if (!function_exists('lyttelton_convict_post_type')) {
	function lyttelton_convict_post_type()
	{
		$labels = array(
			'name'                  => 'Convicts',
			'singular_name'         => 'Convict',
			'menu_name'             => 'Gaol Convicts',
			'name_admin_bar'        => 'Convicts',
			'archives'              => 'Gaol Archives',
			'attributes'            => 'Convict Attributes',
			'parent_item_colon'     => 'Parent Convict:',
			'all_items'             => 'All Convicts',
			'add_new_item'          => 'Add New Item',
			'add_new'               => 'Add Record',
			'new_item'              => 'New Item',
			'edit_item'             => 'Edit Item',
			'update_item'           => 'Update Item',
			'view_item'             => 'View Item',
			'view_items'            => 'View Items',
			'search_items'          => 'Search Item',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'Items list',
			'items_list_navigation' => 'Items list navigation',
			'filter_items_list'     => 'Filter items list',
		);
		$args = array(
			'label'               => 'Convict',
			'description'         => 'Lyttelton Gaol Convict',
			'labels'              => $labels,
			'supports'            => array('editor', 'thumbnail', 'comments', 'revisions'),
			'taxonomies'          => array('category', 'post_tag', 'link_category', 'post_format'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => false,
		);
		register_post_type('convict', $args);
	}

	add_action('init', 'lyttelton_convict_post_type', 0);
}

// Register Custom Meta
if (class_exists('\lyttelton_gaol\gaol_metaboxes')){
	new \lyttelton_gaol\gaol_metaboxes();
}

/**
 * Register custom query vars
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
 */
function lyttelton_register_query_vars( $vars ) {
//	$vars[] = 'type';
	$bio_fields = new lyttelton_gaol\fields\bio();

	foreach ($bio_fields->getConstants() as $key => $field) {
		$vars[] = $field['id'];
	}
	return $vars;
}
add_filter( 'query_vars', 'lyttelton_register_query_vars' );

/**
 * Build a custom query based on several conditions
 * The pre_get_posts action gives developers access to the $query object by reference
 * any changes you make to $query are made directly to the original object - no return value is requested
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
 *
 */
function lyttelton_pre_get_posts( $query ) {
	// check if the user is requesting an admin page
	// or current query is not the main query
	if ( is_admin() || ! $query->is_main_query() ){
		return;
	}

//	if ( !is_post_type_archive( 'accommodation' ) ){
	if ( !is_page( 'browse' ) ){
		return;
	}

	// add meta_query elements
	$meta_query = array();

	if( !empty( get_query_var( fields\bio::NAME['id'] ) ) ){
		$meta_query[] = array( 'key' => fields\bio::NAME['id'], 'value' => get_query_var( fields\bio::NAME['id'] ), 'compare' => 'LIKE' );
	}
	if( !empty( get_query_var( fields\bio::SURNAME['id'] ) ) ){
		$meta_query[] = array( 'key' => fields\bio::SURNAME['id'], 'value' => get_query_var( fields\bio::SURNAME['id'] ), 'compare' => 'LIKE' );
	}

	if( count( $meta_query ) > 1 ){
		$meta_query['relation'] = 'AND';
	}
	if( count( $meta_query ) > 0 ){
		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'lyttelton_pre_get_posts', 1 );

function lyttelton_setup() {
	add_shortcode( 'lyttelton_search_form', 'lyttelton_search_form' );
}
add_action( 'init', 'lyttelton_setup' );

function lyttelton_search_form($args)
{
	// The Query
	// meta_query expects nested arrays even if you only have one query
	$sm_query = new WP_Query(array('post_type' => 'convict', 'posts_per_page' => '50', 'meta_query' => array(array('key' => fields\bio::NAME['id']))));

	// The Loop
	if ($sm_query->have_posts()) {
		$entries = array();
		while ($sm_query->have_posts()) {
			$sm_query->the_post();
			$entry_meta = get_post_meta(get_the_ID());

			// populate an array of all occurrences (non duplicated)
			if (!in_array($entry_meta, $entries)) {
				$entries[] = $entry_meta;
			}
		}
	} else {
		echo 'No accommodations yet!';
		return;
	}

	/* Restore original Post Data */
	wp_reset_postdata();

	if (count($entries) == 0) {
		return;
	}

	asort($entries);

	$select_city = '<select name="city" style="width: 100%">';
	$select_city .= '<option value="" selected="selected">' . __('Select city', 'smashing_plugin') . '</option>';
	foreach ($entries as $entry) {
		$select_city .= '<option value="' . $entry[fields\bio::SURNAME]['id'][0] . '">' . $entry[fields\bio::SURNAME]['id'][0] . '</option>';
	}
	$select_city .= '</select>' . "\n";

	echo $select_city;

	reset($entries);
}

// CONVICT IMPORTING
if (isset($_GET['do_the_import']) && is_admin()){
    new \lyttelton_gaol\gaol_importer($_GET['do_the_import']);
}
