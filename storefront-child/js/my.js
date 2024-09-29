jQuery(document).ready(function(){
	jQuery(document).on("click", '.changecountry', function(event) {
		var country = jQuery(this).val();
		var idwidj = jQuery(this).attr('idwidj');
		if(country > 0){
			 jQuery.ajax({
             type : "POST",
             dataType : "json",
             url : "/wp-admin/admin-ajax.php",
             data : {
				 action: "get_cities",
				 country: country,
				 idwidj: idwidj
				 },
             success: function(response) {
				jQuery('#cities-'+idwidj).html(response);
				jQuery('#select_first-'+idwidj).hide();  
                }
			});
		} else {
			jQuery('#cities-'+idwidj).html('');
			jQuery('#select_first-'+idwidj).show();
		}
	});
	
	
		jQuery(document).on("click", '.changecity', function(event) {
		var city = jQuery(this).val();
		var idwidj = jQuery(this).attr('idwidj');
		var lat = jQuery(this).attr('attr-lat');
		var lng = jQuery(this).attr('attr-lng');
		jQuery('#loading-'+idwidj).show();
		if(city > 0){
			
			 jQuery.ajax({
             type : "POST",
             dataType : "html",
             url : "/wp-admin/admin-ajax.php",
             data : {
				 action: "get_weather",
				 city: city,
				 idwidj: idwidj,
				 lat: lat,
				 lng: lng
				 },
             success: function(response) {
				jQuery('#'+idwidj).html(response);
				jQuery('#loading-'+idwidj).hide();
				 
                }
			});
		} else {
			jQuery('#'+idwidj).html('');
			jQuery('#loading-'+idwidj).hide();
			
		}
		
	});
	
	jQuery(document).on("click", '.search_do', function(event) {
		var city = jQuery(this).val();
		jQuery('#loading-search').show();
		if(city ){
			jQuery('#ajax-search-answ').html('');
			 jQuery.ajax({
             type : "POST",
             dataType : "html",
             url : "/wp-admin/admin-ajax.php",
             data : {
				 action: "get_citys_wpdb",
				 term_id: city,
				 is_ajax: 1
				 },
             success: function(response) {
				
				jQuery('#ajax-search-answ').html(response);
				jQuery('#loading-search').hide();
				 
                }
			});
		} 
		
	});
});
