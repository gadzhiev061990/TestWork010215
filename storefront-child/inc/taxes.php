<?php 
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

function register_countries(){
	$labels = array(
		'name'                     => 'Countries',
		'singular_name'            => 'Country', 
		'menu_name'                => 'Countries', 
		'all_items'                => 'All Countries',
		'edit_item'                => 'Edit Country',
		'view_item'                => 'View Country', 
		'update_item'              => 'Update Country',
		'add_new_item'             => 'Add New Country',
		'new_item_name'            => 'New Country Name',
		'parent_item'              => 'Parent Country',
		'parent_item_colon'        => 'Parent Country:',
		'search_items'             => 'Search Countries',
		'popular_items'            => 'Popular Countries', 
		'separate_items_with_commas' => 'Separate countries with commas',
		'add_or_remove_items'      => 'Add or remove countries',
		'choose_from_most_used'    => 'Choose from the most used countries',
		'not_found'                => 'No countries found',
		'back_to_items'            => '← Back to countries',
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'publicly_queryable' => true,
		'show_in_nav_menus' => true,
		'hierarchical' => true
	);
	register_taxonomy( 'countries', 'cities', $args );

}
add_action( 'init', 'register_countries' );