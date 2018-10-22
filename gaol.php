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

function lyttelton_setup() {
	add_shortcode( 'lyttelton_search_form', 'lyttelton_search_form' );
	add_filter( 'query_vars', 'lyttelton_register_query_vars' );
	add_action( 'pre_get_posts', 'lyttelton_pre_get_posts', 1 );
}
add_action( 'init', 'lyttelton_setup' );
add_action('init', 'lyttelton_convict_post_type', 0);

// Register Custom Post Type
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

// Register Custom Meta
new \lyttelton_gaol\gaol_metaboxes();

/**
 * Register custom query vars
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
 */
function lyttelton_register_query_vars( $vars ) {
	$bio_fields = new lyttelton_gaol\fields\bio();
	$conviction_fields = new lyttelton_gaol\fields\bio();
	$gazette_fields = new fields\gazette();
	$all_fields = $bio_fields->getConstants();// + $conviction_fields->getConstants() + $gazette_fields->getConstants();

	foreach ($all_fields as $key => $field) {
		$vars[] = $field['id'];
	}
	// extra vars for other search options
	$vars[] = 'search_by';
	$vars[] = 'search-mode';

	$vars[] = fields\conviction::OFFENCE['id'];

	return $vars;
}

function lyttelton_search_form($args)
{
	include 'gaol_search_form.php';
}

/**
 * Build a custom query based on several conditions
 * The pre_get_posts action gives developers access to the $query object by reference
 * any changes you make to $query are made directly to the original object - no return value is requested.
 * Custom Search:
 * @link https://www.smashingmagazine.com/2016/03/advanced-wordpress-search-with-wp_query/
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
 */
function lyttelton_pre_get_posts( $query ) {
	// check if the user is requesting an admin page
	// or current query is not the main query
	if ( is_admin() || ! $query->is_main_query() ){
		return;
	}

	if ( !is_post_type_archive( 'convict' ) ){
		return;
	}

	// add meta_query elements
	$meta_query = array();

	$bio_fields = new fields\bio();
	$con_fields = new fields\conviction();
	$gaz_fields = new fields\gazette();
	$all_fields = $bio_fields->getConstants() + $con_fields->getConstants() + $gaz_fields->getConstants();

	$search_by = get_query_var('search_by');

	switch ($search_by){
		case 'person':
			foreach ($all_fields as $field) {
				if (!empty(get_query_var($field['id']))) {
					$meta_query[] = array('key' => $field['id'], 'value' => get_query_var($field['id']), 'compare' => 'LIKE');
				}
			}

			if (count($meta_query) > 1) {
				$meta_query['relation'] = 'AND';
			}
			break;

		case 'conviction':
			foreach (get_query_var(fields\conviction::OFFENCE['id']) as $param){
				$meta_query[] = array('key' => 'convictions', 'value' => "$param", 'compare' => 'RLIKE');
			}

			if (count($meta_query) > 1) {
				$meta_query['relation'] = get_query_var('search-mode');
			}
			break;

		default:
			break;
	}

	if (count($meta_query) > 0) {
		$query->set('meta_query', $meta_query);
		$query->set('nopaging', true);
	}
}

// CONVICT IMPORTING
if (isset($_GET['do_the_import']) && is_admin()){
    new \lyttelton_gaol\gaol_importer($_GET['do_the_import']);
}
