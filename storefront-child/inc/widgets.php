<?php 
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class weather_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'weather_widget',
            __( 'Weather WIdget', 'storefront-child' ),
			[
                'description' => __( 'Sample widget based on WPBeginner Tutorial', 'storefront-child' ),
            ]
        );
    }
 
    // Creating widget front-end
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
 
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
		$countries = get_countries();
		if($countries){
		$id_widg = mt_rand(1000, 9999);?>
			<div class="centerwidg">
				
					<span class="widgsubh"><h4><?php _e( 'Select Country', 'storefront-child' );?></h4></span>
					<select class="countries-widg">
						<option class="changecountry" idwidj="<?php echo $id_widg;?>" value="0"><?php echo __( 'Select country...', 'storefront-child' );?></option>
						<?php foreach($countries as $country){?>
							<option class="changecountry" idwidj="<?php echo $id_widg;?>" value="<?php echo $country->term_id;?>"><?php echo __( $country->name, 'storefront-child' );?></option>
						<?php } ?>
					</select>
				
			
					<span class="widgsubh"><h4><?php _e( 'Select City', 'storefront-child' );?></h4></span>
				
						<h5 id="select_first-<?php echo $id_widg;?>"><?php _e( 'Please, select country first', 'storefront-child' );?></h5>
						<div id="cities-<?php echo $id_widg;?>"></div>
						<div style="display:none;" class="load-spin" id="loading-<?php echo $id_widg;?>"><img src="<?php echo get_stylesheet_directory_uri();?>/images/loading.gif"></div>
						<div id="<?php echo $id_widg;?>" class="resultswidg"></div>
						
				
			</div>
		 <?php } 
     
       
        
		echo $args['after_widget'];
    }
 
    // Widget Settings Form
    public function form( $instance ) {
        if ( isset( $instance['title'] ) ) {
            $title = $instance['title'];
        } else {
            $title = __( 'New title', 'storefront-child' );
        }
 
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php _e( 'Title:', 'storefront-child' ); ?>
            </label>
            <input
                    class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                    name="<?php echo $this->get_field_name( 'title' ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $title ); ?>"
            />
        </p>
        <?php
    }
 
   
    public function update( $new_instance, $old_instance ) {
        $instance          = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
        return $instance;
    }
 
   
}
 

function load_weather_widget() {
    register_widget( 'weather_widget' );
}
 
add_action( 'widgets_init', 'load_weather_widget' );