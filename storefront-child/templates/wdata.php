<?php
/**
* Template Name: Weather data page
*/
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
		<div class="search row">
			<div class="col-lg-12">
				<?php echo get_cities_wpdb_search();?>
			</div>
		</div>
				
		<div class="weatherblock_main">
		<?php $table_city = get_citys_wpdb();
			if($table_city){?>
			<div class="filter row">
				<div class="col-md-3 col-sm-6 col-xs-6 d-sm-none d-md-block tablehead"><?php _e('City' , 'storefront-child');?></div>
				<div class="col-md-3 col-sm-6 col-xs-6 d-sm-none d-md-block tablehead "><?php _e('Country' , 'storefront-child');?></div>
				<div class="col-md-3 col-sm-6 col-xs-6 d-sm-none d-md-block tablehead "><?php _e('Temperature' , 'storefront-child');?></div>
				<div class="col-md-3 col-sm-6 col-xs-6 d-sm-none d-md-block tablehead"><?php _e('Pressure' , 'storefront-child');?></div>
			</div>
			<div style="display:none;" class="load-spin" id="loading-search"><img src="<?php echo get_stylesheet_directory_uri();?>/images/loading.gif"></div>
			<div class="ajax-citys row" id="ajax-search-answ">
				<?php echo $table_city;?>
			</div>
		</div>
			<?php } ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
