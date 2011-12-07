function sbLocationsDrawAdminMap() {
	if($('#sb-location-admin-map').length == 0) {return false;}

	lat = parseFloat($('#sb_location_geocode_latitude').val());
	lon = parseFloat($('#sb_location_geocode_longitude').val());
	if(isNaN(lat) || isNaN(lon)) {return false;}

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

function sbLocationsSetLatLonDisplayValues() {
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

function sbLocationsEnableNewForm() {
	
	var defaultValue = 'Title';
	var titleInput   = $('#sb_locations_new_location_title');
	var theForm      = $('.sb-locations-admin-new-form');
	
	// overide form submit
	theForm.submit(function() {
		sbLocationsSubmitNewForm($(this));
		return false;
	});
	
	// capture button click
	$('.sb-locations-new-location').click(function(){
		$('.a-options.sb-locations-admin-new-ajax').css('display', 'block');
		titleInput.addClass('a-default-value');
		
		if(titleInput.val() == '') {
			titleInput.val(defaultValue);
		}
		
		return false;
	});
	
	// capture cancel click
	$('.sb-locations-admin-new-ajax .a-cancel').click(function() {
		$('.a-options.sb-locations-admin-new-ajax').css('display', 'none');
		titleInput.val('');
		return false;
	});
	
	titleInput.focus(function() {
		if(titleInput.val() == defaultValue) {
			titleInput.removeClass('a-default-value');
			titleInput.val('');
		}
	});
	
	titleInput.blur(function() {
		if(titleInput.val() == '') {
			titleInput.addClass('a-default-value');
			titleInput.val(defaultValue);
		}
	});
}

function sbLocationsSubmitNewForm(form) {
	$.post(form.attr('action'), form.serialize(), function(data) {
		if(data.status == true) {
			window.location = data.redirect_url;
		} else {
			form.find('.a-act-as-submit').removeClass('a-busy');
			alert('Something went wrong when creating your location');
		}
	});
	return false;
}

function sbLocationsSetupEditMap() {
	var addressLookup = '//' + window.location.hostname + '/sb-locations-lookup/address';
	sbLocationsSetLatLonDisplayValues();
	
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
				sbLocationsSetLatLonDisplayValues();
				sbLocationsDrawAdminMap();
			}
			
			$('.sb-geocode-lookup').removeClass('a-busy');
			$('.sb-geocode-lookup').removeClass('icon');
		});
		
		return false;
	});
	
	// draw the map on load
	sbLocationsDrawAdminMap();
}