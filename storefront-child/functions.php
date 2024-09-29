<?php
require_once(locate_template('/inc/cpt.php'));
require_once(locate_template('/inc/taxes.php'));
require_once(locate_template('/inc/admin-settings.php'));
require_once(locate_template('/inc/widgets.php'));

add_action( 'wp_enqueue_scripts', 'st_ch_enqueue_styles_scripts' );
function st_ch_enqueue_styles_scripts() {
    $parenthandle = 'storefront-style'; // This is 'twenty-twenty-one-style' for the Twenty Twenty-one theme.
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(), // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'custom-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
	wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri().'/css/bootstrap.min.css', time());
	wp_enqueue_script( 'my_js', get_stylesheet_directory_uri().'/js/my.js', array('jquery'), time() );
	wp_enqueue_script( 'bootstrap-js', get_stylesheet_directory_uri().'/js/bootstrap.min.js', array('jquery'), time() );
	
}


add_action('wp_ajax_get_citys_wpdb' ,'get_citys_wpdb');
add_action('wp_ajax_nopriv_get_citys_wpdb','get_citys_wpdb');
function get_citys_wpdb(){
	$output = '';
	global $wpdb;
	$pref = $wpdb->prefix;
	$query = "select wpp.ID, wpp.post_title,
					wpmt.meta_value as lat,
					wpmd.meta_value as lng,
					wptr.term_taxonomy_id,
					wptrm.name as country
						from ".$pref."posts wpp
						left join ".$pref."postmeta wpmt on wpmt.post_id = wpp.ID
						left join ".$pref."postmeta wpmd on wpmd.post_id = wpp.ID
						left join ".$pref."term_relationships wptr on wptr.object_id = wpp.ID
						left join ".$pref."terms wptrm on wptr.term_taxonomy_id = wptrm.term_id
					where wpmt.meta_key = '_city-lat' 
					and wpmd.meta_key = '_city-lng'
					and post_status ='publish'
					and post_type = 'cities' ";
	if(isset($_POST['term_id']) && $_POST['term_id'] != 'all'){
		$query .= " and wpp.ID = '".$_POST['term_id']."'";
	}
	$result = $wpdb->get_results($query);
	if($result){
		foreach($result as $res){
			$weather = get_weather_data($res->lat, $res->lng);
			$temp = '-';
			$press = '-';
			if(is_object($weather)){
				$temp = $weather->main->temp;
				$press = $weather->main->pressure;
			}
			
			$output .= '<div class="option one col-md-3 col-sm-6 col-xs-6">'.$res->post_title.'</div>';
			$output .= '<div class="option two col-md-3 col-sm-6  d-sm-none d-md-block">'.$res->country.'</div>';
			$output .= '<div class="option three col-md-3 col-sm-6 col-xs-6">'.$temp.' °C</div>';
			$output .= '<div class="option four col-md-3 col-sm-6 d-sm-none d-md-block">'.$press.'Pa</div>';
		}
	}
	if(isset($_POST['is_ajax'])){
		echo $output;
		wp_die();
	} else {
		return $output;
	}
}

function get_weather_data($lat,$lng){
	$api_key = html_entity_decode( get_option( 'api_weather_key' ) );
	if(!$api_key){
		return false;
	} 
	$city_weather = get_city_weather($lat, $lng, $api_key);
	return $city_weather;
	
}

function get_city_weather($lat, $lng, $api_key, $type=false){
	$myarr = false;
	if($lat && $lng && $api_key ){
		$url ='https://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lng.'&appid='.$api_key.'&units=metric';
		if($type == 'html'){
			$url = $url.'&mode=html';
		}
		$myarr = file_get_contents($url);
		if($type != 'html'){
			$myarr = json_decode($myarr);
		}
	}
		return $myarr;
}
function get_cities_wpdb_search(){
	global $wpdb;
	$output = '';
	$pref = $wpdb->prefix;
	$q = "select ID, post_title from ".$pref."posts where post_status = 'publish' and post_type='cities'";
	$result = $wpdb->get_results($q);
	if($result){
		$output .= '<span class="cityseltext">'._e('Please, select Your city...', 'storefront-child').'</span>';
		$output .= '<select name="cityselect_s" id="cityselect_s">';
		$output .= '<option class="search_do" value="all">'.__('All Cities','storefront-child').'</option>';
			foreach($result as $res){
				$output .= '<option class="search_do" value="'.$res->ID.'">'.$res->post_title.'</option>';
			}
		$output .= '</select>';
	}
	return $output;
}
function get_countries() {
	$output = false;
	$args = array( 
		'taxonomy' => 'countries', 
		'hide_empty' => false 
	);
	$terms = get_terms($args);
	if($terms){
		$output = $terms;
	}
	return $output;
}

add_action('wp_ajax_get_cities', 'get_cities_by_country');
add_action('wp_ajax_nopriv_get_cities', 'get_cities_by_country');
function get_cities_by_country(){
	$output = '';
	$country = $_POST['country'];
	if(!$country){
		echo $output;
		wp_die();
	}
	$idwidj = $_POST['idwidj'];
	$args = array(
		'post_type' => 'cities',
		'posts_per_page' => -1,
		'post_status'=> 'publish',
		'tax_query' => array(
			array(
				'taxonomy' => 'countries',
				'field' => 'id',
				'terms' => $country,
				),
			),
		);
		
		$query = new WP_Query($args);
		if($query->have_posts()){
			$output .= '<select class="cities-widg">';
			$output .= '<option idwidj="'.$idwidj.'" class="changecity">'. __ ('Select City...','storefront-child' ).'</option>';
			while($query->have_posts()){
				$query->the_post();
				$lat = get_post_meta(get_the_ID(), '_city-lat',true);
				$lng = get_post_meta(get_the_ID(), '_city-lng',true);
				if($lat && $lng){
					$output .= '<option attr-lat="'.$lat.'" attr-lng="'.$lng.'" idwidj="'.$idwidj.'" value="'.get_the_ID().'" class="changecity">'.get_the_title().'</option>';
				}
			}
			$output .= '</select>';
		}
		echo json_encode($output);
		wp_die();
}

add_action('wp_ajax_get_weather', 'get_weather_widget');
add_action('wp_ajax_nopriv_get_weather', 'get_weather_widget');
function get_weather_widget(){
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$str = '';
	$api_key = html_entity_decode( get_option( 'api_weather_key' ) );
	if(!$api_key){
		if(current_user_can('level_10')){
			$str = __ ('Error API Key. Check Your Openweather API key in general settings in console.', 'storefront-child' );
		} else {
			$str = __ ('Error data ', 'storefront-child' );
		}
		
	} else {
		$str = file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lng.'&appid='.$api_key.'&units=metric&mode=html');
	}
	echo $str;
	wp_die();
}