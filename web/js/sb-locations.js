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
			alert('The location title can\'t be empty and must not have been used before');
		}
	});
	return false;
}

function sbLocationsSetupEditMap() {
	var addressLookup = '//' + window.location.hostname + '/sb-locations-lookup/address';
	//sbLocationsSetLatLonDisplayValues();
	
	// get geocode lookup
	$('.sb-geocode-lookup').click(function() {
		address = '';
		
		if($('#sb_location_address_line1').val() != '') {
			address = address + ', ' + $('#sb_location_address_line1').val();
		}
		
		if($('#sb_location_address_line2').val() != '') {
			address = address + ', ' + $('#sb_location_address_line2').val();
		}
		
		if($('#sb_location_address_town_city').val() != '') {
			address = address + ', ' + $('#sb_location_address_town_city').val();
		}
		
		if($('#sb_location_address_county').val() != '') {
			address = address + ', ' + $('#sb_location_address_county').val();
		}
		
		if($('#sb_location_address_state').val() != '') {
			address = address + ', ' + $('#sb_location_address_state').val();
		}
		
		if($('#sb_location_address_postal_code').val() != '') {
			address = address + ', ' + $('#sb_location_address_postal_code').val();
		}
		
		if($('#sb_location_address_country').val() != '') {
			address = address + ', ' + $('#sb_location_address_country').val();
		}
						
		$.getJSON(addressLookup + '/' + encodeURIComponent(address), function(data){
			if(data.status == 'OK') {
				$('#sb_location_geocode_latitude').val(parseFloat(data.results.latitude));
				$('#sb_location_geocode_longitude').val(parseFloat(data.results.longitude));
				//sbLocationsSetLatLonDisplayValues();
				sbLocationsDrawAdminMap();
			} else {
				alert('Unable to find address');
			}
			
			$('.sb-geocode-lookup').removeClass('a-busy');
			$('.sb-geocode-lookup').removeClass('icon');
		});
		
		return false;
	});
	
	// draw the map on load
	sbLocationsDrawAdminMap();
}

function sbLocationsSetupFormChangeDetection() {
	/* @TODO
	 * This method should detect changes to the form and alert the user
	 * if they try to navigate away or update images before saving.
	 */
}

function sbLocationsLoadMap(markersUrl) {
  
  var map,alliw = new Array(),default_zoom = 7,ctr;
  var load_places = function(url, id){
    $.getJSON(url, function(data) {
      var n = 1;
      var bounds = new google.maps.LatLngBounds();
      var myLatLng = null;
      $.each(data, function(k, e) {
        myLatLng = new google.maps.LatLng(e.lat,e.lng);
        bounds.extend(myLatLng);
        
        var im = new google.maps.MarkerImage(e.icon.url,
          new google.maps.Size(e.icon.size.one, e.icon.size.two),
          new google.maps.Point(e.icon.point1.one, e.icon.point1.two),
          new google.maps.Point(e.icon.point2.one, e.icon.point2.two)
        );

        var shadow = new google.maps.MarkerImage(e.shadow.url,
          new google.maps.Size(e.shadow.size.one, e.shadow.size.two),
          new google.maps.Point(e.shadow.point1.one, e.shadow.point1.two),
          new google.maps.Point(e.shadow.point2.one, e.shadow.point2.two)
        );

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: im,
            shadow: shadow,
            title : e.name,
            zIndex : n
        });

        var infowindow = new google.maps.InfoWindow({
          content: e.description
        });

        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map,marker);
        });
        n++;
      });
        
      map.fitBounds(bounds);
    });
  }
  
  map = new google.maps.Map(document.getElementById('sb-locations-map-container'),{
		mapTypeId : google.maps.MapTypeId.ROADMAP,
    zoom: 7
	});

	load_places(markersUrl);
}