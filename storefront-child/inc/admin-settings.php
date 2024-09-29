<?php 
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

add_action( 'admin_init', 'add_api_key_settings' );


function add_api_key_settings() 
{
    register_setting( 
        'general', 
        'api_weather_key',
        'esc_html'
    );
    add_settings_section( 
        'site-guide', 
        'Openweather API key', 
        '__return_false', 
        'general' 
    );
    add_settings_field( 
        'api_weather_key', 
        'Enter Your Openweather API Key', 
        'get_field_weather_key', 
        'general', 
        'site-guide' 
    );
}    
function get_field_weather_key() 
{
    $curr_api_key = html_entity_decode( get_option( 'api_weather_key' ) );
	echo '<input type="text" name="api_weather_key" id="api_weather_key" value="'.$curr_api_key.'">';
}