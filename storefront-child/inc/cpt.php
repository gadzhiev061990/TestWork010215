<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
function register_cities(){
	$labels = array(
		'name'                     => 'Cities',
		'singular_name'            => 'City', 
		'add_new'                  => 'Add City',
		'add_new_item'             => 'Add New City',
		'edit_item'                => 'Edit City',
		'new_item'                 => 'New City',
		'view_item'                => 'View City', 
		'search_items'             => 'Find City',
		'not_found'                => 'No Cities found',
		'not_found_in_trash'       => 'No Cities found in Trash',
		'parent_item_colon'        => 'Parent City', 
		'all_items'                => 'All Cities', 
		'archives'                 => 'City Archives', 
		'menu_name'                => 'Cities', 
		'name_admin_bar'           => 'City', 
		'view_items'               => 'View Cities', 
		'attributes'               => 'City Attributes', 
	 
		
		'insert_into_item'         => 'Insert into City',
		'uploaded_to_this_item'    => 'Uploaded to this City',
		'featured_image'           => 'City Featured Image',
		'set_featured_image'       => 'Set City Featured Image',
		'remove_featured_image'    => 'Remove City Featured Image',
		'use_featured_image'       => 'Use as City Featured Image',
	 
		
		'item_updated'             => 'City updated.',
		'item_published'           => 'City published.',
		'item_published_privately' => 'City published privately.',
		'item_reverted_to_draft'   => 'City reverted to draft.',
		'item_scheduled'           => 'City scheduled.',
	);
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'menu_icon'			 => 'dashicons-flag',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => 5,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custof-fields' ),
	);
	register_post_type( 'cities', $args );
}
add_action( 'init', 'register_cities' );


function add_coordinates_metabox( $post ){
    add_meta_box( 'coord_meta_box', 'City coordinates', 'coord_create_meta_box', 'cities', 'side', 'low' );
}
add_action( 'add_meta_boxes_cities', 'add_coordinates_metabox' );

function coord_create_meta_box( $post ){
  $city_lng = get_post_meta( $post->ID, '_city-lng', true); 
  $city_lat = get_post_meta( $post->ID, '_city-lat', true); ?>
  <div class="inside">
	<div class="coordcont">
		<p>
			<label for="city-lat">LAT</label>
			<input type="text" id="city-lat" name="city-lat" value="<?php echo $city_lat; ?>" /> 
		</p>
		<p>
			<label for="city-lng">LNG</label>
			<input type="text" id="city-lng" name="city-lng" value="<?php echo $city_lng; ?>" /> 
		</p>
	</div>
  </div>
<?php }


function save_coord_meta_box_data( $post_id ){
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
	}

  if ( isset( $_REQUEST['city-lat'] )) {
    update_post_meta( $post_id, '_city-lat', sanitize_text_field( $_POST['city-lat'] ) );
  }
  if ( isset( $_REQUEST['city-lng'] )) {
    update_post_meta( $post_id, '_city-lng', sanitize_text_field( $_POST['city-lng'] ) );
  }
  
}
add_action( 'save_post_cities', 'save_coord_meta_box_data', 10, 2 );