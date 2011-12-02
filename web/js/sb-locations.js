$(document).ready(function() {
	
	var addressLookup = '//' + window.location.hostname + '/sb-locations-lookup/address';
	setLatLonDisplayValues();
	
	// get geocode lookup
	$('.sb-geocode-lookup').click(function() {
		address = $('#sb_location_address_line1').val() + ", " +
							$('#sb_location_address_line2').val() + ", " +
							$('#sb_location_address_town_city').val() + ", " +
							$('#sb_location_address_county').val() + ", " +
							$('#sb_location_address_state').val() + ", " +
							$('#sb_location_address_country').val() + ", " +
							$('#sb_location_address_postal_code').val();
						
		$.getJSON(addressLookup + '/' + encodeURIComponent(address), function(data){
			if(data.status == 'OK') {
				$('#sb_location_geocode_latitude').val(parseFloat(data.results.latitude));
				$('#sb_location_geocode_longitude').val(parseFloat(data.results.longitude));
				setLatLonDisplayValues();
				drawAdminMap();
			}
			
			$('.sb-geocode-lookup').removeClass('a-busy');
			$('.sb-geocode-lookup').removeClass('icon');
		});
		
		return false;
	});
	
	// draw the map on load
	drawAdminMap();
	
	function drawAdminMap() {
		if($('#sb-location-admin-map').length == 0) { return false; }
		
		lat = parseFloat($('#sb_location_geocode_latitude').val());
		lon = parseFloat($('#sb_location_geocode_longitude').val());
		if(isNaN(lat) || isNaN(lon)) { return false; }
		
		ctr = new google.maps.LatLng(lat, lon);
		map = new google.maps.Map(document.getElementById('sb-location-admin-map'),{
			zoom : 13,
			center : ctr,
			mapTypeId : google.maps.MapTypeId.ROADMAP,
			disableDefaultUI : false,
			navigationControl : false,
			navigationControlOptions : {
				style : google.maps.NavigationControlStyle.SMALL
			}
		});
		
		var myLatLng = new google.maps.LatLng(lat,lon);
		
		var marker = new google.maps.Marker({
				position: myLatLng,
				map: map
		});
		
		return true;
	}
	
	function setLatLonDisplayValues()
	{
		lat = parseFloat($('#sb_location_geocode_latitude').val());
		lon = parseFloat($('#sb_location_geocode_longitude').val());
		
		if(isNaN(lat) || isNaN(lon)) { 
			lat = '~';
			lon = '~';
		}
		
		$('#geocode_latitude_value').html(lat);
		$('#geocode_longitude_value').html(lon);
		return true;
	}
	
});