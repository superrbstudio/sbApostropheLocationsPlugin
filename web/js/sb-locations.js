
var sbOpenStreetMapAdminMap;
var sbOpenStreetMapAdminMarkers;

function sbLocationsDrawAdminMap(mapSystem, mapIcon) {
	if($('#sb-location-admin-map').length == 0) {return false;}
	
	// do we use Google Maps or Open Street Maps
	if(mapSystem == undefined) { mapSystem = 'sbGoogleMap'; }
  
  // get the co-ordinates
  lat = parseFloat($('#sb_location_geocode_latitude').val());
  lon = parseFloat($('#sb_location_geocode_longitude').val());
	if(isNaN(lat) || isNaN(lon)) {return false;}
	
	if(mapSystem == 'sbGoogleMap') {
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
    
    var im = new google.maps.MarkerImage(mapIcon.icon.url,
      new google.maps.Size(mapIcon.icon.size.one, mapIcon.icon.size.two),
      new google.maps.Point(mapIcon.icon.point1.one, mapIcon.icon.point1.two),
      new google.maps.Point(mapIcon.icon.point2.one, mapIcon.icon.point2.two)
    );

    var shadow = new google.maps.MarkerImage(mapIcon.shadow.url,
      new google.maps.Size(mapIcon.shadow.size.one, mapIcon.shadow.size.two),
      new google.maps.Point(mapIcon.shadow.point1.one, mapIcon.shadow.point1.two),
      new google.maps.Point(mapIcon.shadow.point2.one, mapIcon.shadow.point2.two)
    );
	
		var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
        icon: im,
        shadow: shadow
		});
	
		return true;
	}
  
  if(mapSystem == 'sbOpenStreetMap') {
    
    // set up or find the map
    if(sbOpenStreetMapAdminMap == undefined) { 
      sbOpenStreetMapAdminMap = new OpenLayers.Map("sb-location-admin-map");
      sbOpenStreetMapAdminMap.addLayer(new OpenLayers.Layer.OSM());
    }
    
    // set up or find the markers
    if(sbOpenStreetMapAdminMarkers == undefined) {
      sbOpenStreetMapAdminMarkers = new OpenLayers.Layer.Markers("Markers");
      sbOpenStreetMapAdminMap.addLayer(sbOpenStreetMapAdminMarkers);
    } else {
      sbOpenStreetMapAdminMarkers.destroy();
      sbOpenStreetMapAdminMarkers = new OpenLayers.Layer.Markers("Markers");
      sbOpenStreetMapAdminMap.addLayer(sbOpenStreetMapAdminMarkers);
      sbOpenStreetMapAdminMarkers.redraw();
    }
    
    // draw marker on map
    var size = new OpenLayers.Size(21,25);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('http://www.openlayers.org/dev/img/marker.png',size,offset);
    sbOpenStreetMapAdminMarkers.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(lon,lat)
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            sbOpenStreetMapAdminMap.getProjectionObject() // to Spherical Mercator Projection
          ),icon));
 
    //Set start centrepoint and zoom    
    var lonLat = new OpenLayers.LonLat(lon,lat)
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            sbOpenStreetMapAdminMap.getProjectionObject() // to Spherical Mercator Projection
          );
    var zoom=13;
    sbOpenStreetMapAdminMap.setCenter (lonLat, zoom);
    
    $('#sb-location-admin-map .olControlAttribution').css('display', 'none');
    
    return true;
  }
  
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

function sbLocationsSetupEditMap(mapSystem, mapIcon) {
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
		
		// if($('#sb_location_address_state').val() != '') {
		// 	address = address + ', ' + $('#sb_location_address_state').val();
		// }
		
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
				sbLocationsDrawAdminMap(mapSystem, mapIcon);
			} else {
				alert('Unable to find address');
			}
			
			$('.sb-geocode-lookup').removeClass('a-busy');
			$('.sb-geocode-lookup').removeClass('icon');
		});
		
		return false;
	});
	
	// draw the map on load
	sbLocationsDrawAdminMap(mapSystem, mapIcon);
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
        var curPoint = e;
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
        
        if(e.description != '') {
          var infowindow = new google.maps.InfoWindow({
            content: e.description
          });

          google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker);
          });
        }
        
        n++;
      });
      
      if(n > 2) {
        map.fitBounds(bounds);
      } else {
        map.setCenter(myLatLng);
      }
    });
  }
  
  map = new google.maps.Map(document.getElementById('sb-locations-map-container'),{
		mapTypeId : google.maps.MapTypeId.ROADMAP,
        zoom: 15,
        scrollwheel: false
	});

	load_places(markersUrl);
}

var sbSingleLocationsMapLoads = {};
var sbSingleLocationMaps = {};

function sbSingleLocationMapSlot(params) {
  
  var divId = params.divId;
  
  if($('#' + params.divId).length == 0) {return false;}
	
	// do we use Google Maps or Open Street Maps
	if(params.mapSystem == undefined) { params.mapSystem = 'sbGoogleMap'; }
  
  // get the co-ordinates
  lat = parseFloat(params.latitude);
  lon = parseFloat(params.longitude);
	if(isNaN(lat) || isNaN(lon)) {return false;}
	
	if(params.mapSystem == 'sbGoogleMap') {
		ctr = new google.maps.LatLng(lat, lon);
		sbSingleLocationMaps[divId] = new google.maps.Map(document.getElementById(divId),{
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
    
    var im = new google.maps.MarkerImage(params.mapIcon.icon.url,
      new google.maps.Size(params.mapIcon.icon.size.one, params.mapIcon.icon.size.two),
      new google.maps.Point(params.mapIcon.icon.point1.one, params.mapIcon.icon.point1.two),
      new google.maps.Point(params.mapIcon.icon.point2.one, params.mapIcon.icon.point2.two)
    );

    var shadow = new google.maps.MarkerImage(params.mapIcon.shadow.url,
      new google.maps.Size(params.mapIcon.shadow.size.one, params.mapIcon.shadow.size.two),
      new google.maps.Point(params.mapIcon.shadow.point1.one, params.mapIcon.shadow.point1.two),
      new google.maps.Point(params.mapIcon.shadow.point2.one, params.mapIcon.shadow.point2.two)
    );
	
		var marker = new google.maps.Marker({
				position: myLatLng,
				map: sbSingleLocationMaps[divId],
        icon: im,
        shadow: shadow
		});
    
    if(params.description != '') {
      var infowindow = new google.maps.InfoWindow({
        content: $('<div>').html(params.description).text()
      });

      google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(sbSingleLocationMaps[divId],marker);
      });
    }
	
    sbSingleLocationsMapLoads[divId] = true;
		return true;
	}
  
  if(params.mapSystem == 'sbOpenStreetMap') {
    
    // The overlay layer for our marker, with a simple diamond as symbol
    var overlay = new OpenLayers.Layer.Vector('Overlay', {
        styleMap: new OpenLayers.StyleMap({
            externalGraphic: 'http://www.openlayers.org/dev/img/marker.png',
            graphicWidth: 20, graphicHeight: 24, graphicYOffset: -24,
            title: params.title
        })
    });

    // The location of our marker and popup. We usually think in geographic
    // coordinates ('EPSG:4326'), but the map is projected ('EPSG:3857').
    var myLocation = new OpenLayers.Geometry.Point(lon, lat)
        .transform('EPSG:4326', 'EPSG:3857');

    // We add the marker with a tooltip text to the overlay
    overlay.addFeatures([
        new OpenLayers.Feature.Vector(myLocation, {tooltip: params.title})
    ]);

    // A popup with some information about our location
    var popup = new OpenLayers.Popup.FramedCloud("Popup" + divId, 
        myLocation.getBounds().getCenterLonLat(), null,
        $('<div>').html(params.description).text(), null,
        true // <-- true if we want a close (X) button, false otherwise
    );

    // Finally we create the map
    sbSingleLocationMaps[divId] = new OpenLayers.Map({
        div: divId, projection: "EPSG:3857",
        layers: [new OpenLayers.Layer.OSM(), overlay],
        center: myLocation.getBounds().getCenterLonLat(), zoom: 13
    });
    // and add the popup to it.
    if(params.description != '') {
      sbSingleLocationMaps[divId].addPopup(popup);
    }
    
    sbSingleLocationsMapLoads[divId] = true;
    return true;
  }
  
  return true;
}