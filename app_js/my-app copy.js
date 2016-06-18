

// Initialize your app
var myApp = new Framework7({
    //Tell Framework7 to compile templates on app init
    precompileTemplates: true
});

/*
// Render person template to HTML, its template is already compiled and accessible as Template7.templates.personTemplate
var personHTML = Template7.templates.personTemplate({
    name: 'John Doen',
    age: 33,
    position: 'Developer',
    company: 'Apple'
});
*/

// Export selectors engine
var $$ = Dom7;

// Add view
var mainView = myApp.addView('.view-main', {
    // Because we use fixed-through navbar we can enable dynamic navbar
    dynamicNavbar: true
});

var personTemplate = $$('script#personTemplate').html();
var compiledPersonTemplate = Template7.compile(personTemplate);

// Render person template to HTML, its template is already compiled and accessible as Template7.templates.personTemplate
var personHTML = Template7.templates.personTemplate({
    name: 'John Doen',
    age: 33,
    position: 'Developer',
    company: 'Apple'
});


	$$.getJSON('http://brewhound.nz/listBrewAvail.php', function (data) {
		
		//console.log(data);
		
		    $$.each(data, function (key, value) {
			if(value.BeerPhoto!=''){
				var photo = value.BeerPhoto;
			} else {
				var photo = value.BreweryPhoto;
			}

	       $$('#recent').append('<div class="item-content"><div class="item-inner"><li><a href="#" class="create-brew" data-id="'+value.Brewid+'"><img src="http://brewhound.nz/assets/images/'+photo+'" width="50" height="50"/> '+value.Brew+' '+value.Location+' <span data-livestamp="'+value.Date+'"></span></a></li></div></div>');
	        //console.log(value);
	    });
	    	$$('.create-brew').on('click', function () {
		    	//console.log($$(this).data('id'));
		    	
				createBrewPage($$(this).data('id'));
    		});
	});

//$$.getJSON('http://brewhound.nz/listBrewAvail.php', function (data) {
//console.log(data);

/*
    $$.each(data, function (key, value) {
      $$('.data').append('<p>'+value+'</p>');
        console.log(value);
    });
*/

//});

myApp.onPageInit('*', function (page) {
  console.log(page.name + ' initialized');
  //initMap(page.name+'map');
  
  
});


// Callbacks to run specific code for specific pages, for example for About page:
myApp.onPageInit('map', function (page) {
    // run createContentPage func after link was clicked
	//console.log(page);
	//console.log($$('#map2'));
	initMap('map2');
	
});

myApp.onPageInit('person', function (page) {

	$$.getJSON('http://brewhound.nz/listBrewAvail.php', function (data) {
		
		console.log(data);
		
		    $$.each(data, function (key, value) {
	
	       $$('.data').append('<p>'+value.AvID+' '+value.Brew+' '+value.Location+'</p>');
	        //console.log(value);
	    });
	});
	
/*
	$$.getJSON('http://brewhound.nz/listBrews.php', function (data) {
	//console.log(data);

	    $$.each(data, function (key, value) {
	
	       $$('.data').append('<p>'+value+'</p>');
	        //console.log(value);
	    });

	});
*/

}); 


// Callbacks to run specific code for specific pages, for example for About page:
myApp.onPageInit('about', function (page) {
    // run createContentPage func after link was clicked
    $$('.create-page').on('click', function () {
        createContentPage();
    });
});

// Generate dynamic page
var dynamicPageIndex = 0;
function createContentPage() {
	mainView.router.loadContent(
        '<!-- Top Navbar-->' +
        '<div class="navbar">' +
        '  <div class="navbar-inner">' +
        '    <div class="left"><a href="#" class="back link"><i class="icon icon-back"></i><span>Back</span></a></div>' +
        '    <div class="center sliding">Dynamic Page ' + (++dynamicPageIndex) + '</div>' +
        '  </div>' +
        '</div>' +
        '<div class="pages">' +
        '  <!-- Page, data-page contains page name-->' +
        '  <div data-page="dynamic-pages" class="page">' +
        '    <!-- Scrollable page content-->' +
        '    <div class="page-content">' +
        '      <div class="content-block">' +
        '        <div class="content-block-inner">' +
        '          <p>Here is a dynamic page created on ' + new Date() + ' !</p>' +
        '          <p>Go <a href="#" class="back">back</a> or go to <a href="services.html">Services</a>.</p>' +
        '        </div>' +
        '      </div>' +
        '    </div>' +
        '  </div>' +
        '</div>'
    );
	return;
}

// Generate dynamic page
var dynamicPageIndex = 0;
function createBrewPage(id) {
	$$.getJSON('http://brewhound.nz/listBrewData.php?id='+id, function (data) {
		$$.each(data, function (key, value) {
			console.log(value.Brew);
			var brewname = value.Brew;
			var brewphoto = value.BrewPhoto;
			console.log(value[0]);
					mainView.router.loadContent(
				        '<!-- Top Navbar-->' +
				        '<div class="navbar">' +
				        '  <div class="navbar-inner">' +
				        '    <div class="left"><a href="#" class="back link"><i class="icon icon-back"></i><span>Back</span></a></div>' +
				        '    <div class="center sliding">Brew ' + (++dynamicPageIndex) + '</div>' +
				        '  </div>' +
				        '</div>' +
				        '<div class="pages">' +
				        '  <!-- Page, data-page contains page name-->' +
				        '  <div data-page="dynamic-pages" class="page">' +
				        '    <!-- Scrollable page content-->' +
				        '    <div class="page-content">' +
				        '      <div class="content-block">' +
				        '        <div class="content-block-inner">' +
				        '	<div id="brew"></div>'+
				        '          <h2>' + brewname + '</h2>' +
				         '          <p><img src="http://brewhound.nz/assets/images/' + brewphoto + '"/>.</p>' +
				        '          <p>Go <a href="#" class="back">back</a> or go to <a href="services.html">Services</a>.</p>' +
				        '        </div>' +
				        '      </div>' +
				        '    </div>' +
				        '  </div>' +
				        '</div>'
				    );

		});
	});
	return;
}

// MAPS

	

	var map;
	function initMap(id='map') {
		
		    map = new google.maps.Map(document.getElementById(id), {
		      center: {lat:-36.865367, lng: 174.761234},
		      mapTypeControl: false,
		      streetViewControl: false,
		      zoom: 13
		    });
		    
		    //console.log($$("#markers").text());
			
		   
		   var markers = JSON.parse($("#markers").text());
		  
		  //var markers = [];
		    
/*
		    var t = <?php echo $now; ?>.split(/[- :]/);
		    var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
*/

		    var now = '2016-05-09 18:00:00';
			var midnight = '2016-05-09 00:05:00';
			var lastweek = '2016-05-06 00:05:00';
		    //console.log(markers[0].markername);
		    //console.log(d);
		    // create array of marker objects
		    markerobjects = [];
		    	    
		    for (var i = 0; i < markers.length; i++) {

			    var latlng = markers[i][0];
			    var markername = markers[i].markername;
				var link = markers[i].link;
				var latest = markers[i].latest;
				var image = markers[i].image;
				var size = '50x50';
				var url = 'http://brewhound.nz/thumbs/' + size + '/images/' + image;
				var updated = markers[i].updated;
				var region = markers[i].region;
				//console.log(image);
				//console.log(markers[i].region);
				
			    marker = new google.maps.Marker({
					position: latlng,
					map: map,
					icon: url,
					zIndex: i, 
					title: markername,
					 // Custom Attributes / Data / Key-Values
				    updated: updated,
				    region: region
				});
				
				// add marker to array
				//console.log(marker);
				markerobjects.push(marker);
				
				marker.info = new google.maps.InfoWindow({
				  content: '<a href="http://brewhound.nz/location/' + link + '">' + latest + ' @ ' + markername + '</a>'
				  //content: '<a href="http://brewhound.nz/location/' + link + '">' + latest + ' @ ' + markername + '</a>'
				});

				//console.log(markername);
				 
				google.maps.event.addListener(marker, 'click', function() {  
				    var marker_map = this.getMap();
				    this.info.open(marker_map, this);
				    // Note: If you call open() without passing a marker, the InfoWindow will use the position specified upon construction through the InfoWindowOptions object literal.
				});

			}		
			
			$(document).on('click', '#today', function(){
				var bounds = new google.maps.LatLngBounds();
				$.each(markerobjects, function(i, marker) {
				    if( (marker.updated > midnight) && (marker.updated < now) ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});
			
			$(document).on('click', '#thisweek', function(){
				var bounds = new google.maps.LatLngBounds();
				$.each(markerobjects, function(i, marker) {
				    if( (marker.updated > lastweek) && (marker.updated < now) ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});
			
			$(document).on('click', '#reset', function(){
				$.each(markerobjects, function(i, marker) {
				marker.setVisible(true);
				bounds.extend( marker.getPosition() );
				});
				map.fitBounds(bounds);
			});

			$(document).on('click', '#auckland', function(){
				var bounds = new google.maps.LatLngBounds();
				//console.log(marker.region);
				$.each(markerobjects, function(i, marker) {
				    if( marker.region == 'Auckland' ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});

			$(document).on('click', '#hawkesbay', function(){
				var bounds = new google.maps.LatLngBounds();
				//console.log(marker.region);
				$.each(markerobjects, function(i, marker) {
				    if( marker.region == 'Hawkes Bay' ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});			

			$(document).on('click', '#wellington', function(){
				var bounds = new google.maps.LatLngBounds();
				//console.log(marker.region);
				$.each(markerobjects, function(i, marker) {
				    if( marker.region == 'Wellington' ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});	


			$(document).on('click', '#chch', function(){
				var bounds = new google.maps.LatLngBounds();
				//console.log(marker.region);
				$.each(markerobjects, function(i, marker) {
				    if( marker.region == 'Christchurch' ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});	
				
		    var bounds = new google.maps.LatLngBounds();
			
			for (var i = 0; i < markers.length; i++) {
			 		//  And increase the bounds to take this point
				  bounds.extend (new google.maps.LatLng (markers[i][0].lat,markers[i][0].lng));
			}
			
			map.fitBounds(bounds);
	
			
  	}

 


