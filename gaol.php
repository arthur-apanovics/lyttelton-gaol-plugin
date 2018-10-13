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

require('gaol_metaboxes.php');
require('gaol_importer.php');

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

function search_library($template)
{
	global $wp_query;
	$post_type = get_query_var('post_type');
	if( $wp_query->is_search && $post_type == 'library' )
	{
		return locate_template('search-library.php');  //  redirect to archive-search.php
	}
	return $template;
}
add_filter('template_include', 'search_library');

// Register Custom Meta
if (class_exists('\lyttelton_gaol\gaol_metaboxes')){
	new \lyttelton_gaol\gaol_metaboxes();
}

if (isset($_GET['do_the_import'])){
    new \lyttelton_gaol\gaol_importer;
}
