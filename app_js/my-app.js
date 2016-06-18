

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



// infinite scroll

myApp.onPageInit('infinite-scroll', function (page) {
	console.log('infinite-scroll init function');
    // Loading trigger
    var loading = false;
    // Last loaded index, we need to pass it to script
    var lastLoadedIndex = $$('.infinite-scroll .list-block li').length;
    console.log(lastLoadedIndex);
    // Attach 'infinite' event handler
    $$('.infinite-scroll').on('infinite', function () {
	    console.log('on infinite ');
        // Exit, if loading in progress
        if (loading) return;
        // Set loading trigger
        loading = true;
        // Request some file with data
        $$.get('app_infinite-scroll-load.php', {leftIndex: lastLoadedIndex + 1}, function (data) {
            loading = false;
            if (data === '') {
                // Nothing more to load, detach infinite scroll events to prevent unnecessary loadings
                myApp.detachInfiniteScroll($$('.infinite-scroll'));
            }
            else {
                // Append loaded elements to list block
                $$('.infinite-scroll .list-block ul').append(data);
                // Update last loaded index
                lastLoadedIndex = $$('.infinite-scroll .list-block li').length;
            }
        });
    });
});       

// infinite scroll end

	
$$('#login').on('click', function(){
	console.log('ajax login request');
	
});

$$('form#login.ajax-submit').on('submitted', function (e) {
		 
/*
	var formData = $('#brewcheckinid').val();
	console.log(formData);
*/

	var xhr = e.detail.xhr; // actual XHR object

	var data = e.detail.data; // Ajax response from action file
	// do something with response data
	var response = JSON.parse(data);
	console.log(response.success);
	//myApp.alert(response.username, 'Login Attempt');

	if(response.success){
		myApp.alert('yay', 'login successful!');
	} else {
		myApp.alert(response.error, 'login unsuccessful!');
	}

});

$$('#account').on('click', function(){
	$$.get( "app_checklogin.php", function( data ) {
	  var response = JSON.parse(data);
	  console.log(response);

	  if(response.success){
		 mainView.router.loadPage('app_account.php');
	  } else {
	  	myApp.loginScreen(); 
	  }


	});
});


myApp.onPageInit('account', function (page) {
	$$('#logout').on('click', function () {
	//myApp.alert('logout clicked');
	
	$$.get( "app_logout.php", function( data ) {
	  //$( ".result" ).html( data );
	  var response = JSON.parse(data);
	  //console.log(data);
	  myApp.alert(response.message, 'logout');
	});
	
	});
	
/*
	$$.get( "app_logout.php", function( data ) {
	  //$( ".result" ).html( data );
	  console.log(data);
	  myApp.alert(response.message, 'logout');
	});
*/
	});

myApp.onPageInit('index', function (page) {
	console.log(page.name);

	var map;
	map = new google.maps.Map(document.getElementById('map'), {
      center: {lat:-36.865367, lng: 174.761234},
      mapTypeControl: false,
      streetViewControl: false,
      maxZoom: 15,
      zoom: 10
    });

	
	/*
var map;
function initMap(id) {
		console.log('map id = '+id)
	    map = new google.maps.Map(document.getElementById(id), {
	      center: {lat:-36.865367, lng: 174.761234},
	      mapTypeControl: false,
	      streetViewControl: false,
	      zoom: 13
	    });
}
*/
	
	
	myApp.hidePreloader();
});


myApp.onPageBeforeInit('*', function (page) {
  console.log(page.name + ' before init');
    $$('.open-preloader').on('click', function () {
    myApp.showPreloader();
    setTimeout(function () {myApp.hidePreloader();}, 2000);
	});
});

// if page then load map

myApp.onPageInit('*', function (page) {
/*
if(page.name == 'index'){	
		var map;
	map = new google.maps.Map(document.getElementById('map'), {
      center: {lat:-36.865367, lng: 174.761234},
      mapTypeControl: false,
      streetViewControl: false,
      maxZoom: 15,
      zoom: 10
    });
}
*/
  if(page.name == 'brew'){
	var beerID = page.query.beerID;
	var markersjson = $.getJSON( 'http://brewhound.nz/listBrewTaps.php?brew='+beerID, function( data ) {
	var markers = []; // format marker data to array
    $.each( data, function(i, obj) {
	    markers.push(obj);
    });
    
	var map;
	map = new google.maps.Map(document.getElementById('brewmap'), {
      center: {lat:-36.865367, lng: 174.761234},
      mapTypeControl: false,
      streetViewControl: false,
      maxZoom: 15,
      zoom: 10
    });

	for (var i = 0; i < markers.length; i++) {

	    var latlng = markers[i][0];
	    var markername = markers[i].markername;
		var link = markers[i].link;
		var latest = markers[i].latest;
		var image = markers[i].image;
		var size = '50x50';
		var url = 'http://brewhound.nz/thumbs/' + size + '/images/' + image;
		//console.log(markers[i][0]);
		//console.log(markername);
		
	    var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			icon: url,
			zIndex: i, 
			title: markername
		});
		
		//console.log(map);
		
		marker.info = new google.maps.InfoWindow({
		  content: '<a href="http://brewhound.nz/location/' + link + '">' + latest + ' @ ' + markername + '</a>'
		});
		 
		google.maps.event.addListener(marker, 'click', function() {  
		    var marker_map = this.getMap();
		    this.info.open(marker_map, this);
		    // Note: If you call open() without passing a marker, the InfoWindow will use the position specified upon construction through the InfoWindowOptions object literal.
		});
		 
	}	
		
	    var bounds = new google.maps.LatLngBounds();

		for (var i = 0; i < markers.length; i++) {
		//  And increase the bounds to take this point
		bounds.extend (new google.maps.LatLng (markers[i][0].lat,markers[i][0].lng));
		//console.log(markers[i][0].lat,markers[i][0].lng);
		
		map.fitBounds(bounds);
		}
		

	
	});
	
	
  }
  
  myApp.hidePreloader();
});


var map;
function initFrontPageMap() {
	
	    map = new google.maps.Map(document.getElementById('map'), {
	      center: {lat:-36.865367, lng: 174.761234},
	      mapTypeControl: false,
	      streetViewControl: false,
	      zoom: 13
	    });
}

// end brew map



myApp.onPageInit('checkin', function (page) {
	$$("#ratingslider").change(function () {                    
	var newValue = $('#ratingslider').val();
	$$("#rating").html(newValue);
	});
	

	
	$$('form#checkin.ajax-submit').on('submitted', function (e) {
		 
		var formData = $('#brewcheckinid').val();
		console.log(formData);

		var xhr = e.detail.xhr; // actual XHR object
 
		var data = e.detail.data; // Ajax response from action file
		// do something with response data
		var response = JSON.parse(data);
		if(response.success){
			console.log(response.message);
			myApp.alert('Here goes alert text', 'Checkin Created!');
		};

		

	});
	
/*
	$$('input#submitcheckin').submit(function(event) {
	// stop the form from submitting the normal way and refreshing the page
    event.preventDefault();
    console.log('submit');
*/
		 
/*
		 var formData = {
            'brew' : $$('#brewcheckinid').val()
        };
*/
        
/*
         $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'app_submit_checkin.php', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode          : true
        })
                    // using the done promise callback
            .done(function(data) {

                // log data to the console so we can see
                console.log(data); 

                // here we will handle errors and validation messages
            });
*/

// 	 });

});

// AUTOCOMPLETE brew

myApp.onPageInit('checkin', function (page) {
	var fruits = $.getJSON( "http://brewhound.nz/listBrews.php", function( data ) {
	    $.each( data, function(i, value) {
	      //console.log( i + value);
	    })
	
	});
	
var autocompleteDropdownAjax = myApp.autocomplete({
    input: '#autocomplete-dropdown-all',
    openIn: 'dropdown',
    preloader: true, //enable preloader
    valueProperty: 'id', //object's "value" property name
    textProperty: 'name', //object's "text" property name
    limit: 20, //limit to 20 results
    dropdownPlaceholderText: 'start typing',
    expandInput: true, // expand input
    
    onChange: function(autocomplete, value){
	    $$('#brewcheckinid').val(value.id);
    },
    source: function (autocomplete, query, render) {
        var results = [];
        if (query.length === 0) {
            render(results);
            return;
        }
        // Show Preloader
        autocomplete.showPreloader();
        // Do Ajax request to Autocomplete data
        $$.ajax({
            url: 'http://brewhound.nz/listAjaxBrews.php',
            method: 'GET',
            dataType: 'json',
            //send "query" to server. Useful in case you generate response dynamically
            data: {
                query: query
            },
            success: function (data) {

                // Find matched items
                for (var i = 0; i < data.length; i++) {
	                //console.log(data[i])
                    if (data[i].name.toLowerCase().indexOf(query.toLowerCase()) >= 0) results.push(data[i]);
                }
                // Hide Preoloader
                autocomplete.hidePreloader();
                // Render items by passing array with result items
                render(results);
	            }
	        }); 
	       
	    }
	});


});


myApp.onPageInit('locations', function (page) {

	
var autocompleteDropdownAjax = myApp.autocomplete({
    input: '#autocomplete-dropdown-all',
    openIn: 'dropdown',
    preloader: true, //enable preloader
    valueProperty: 'slug', //object's "value" property name
    textProperty: 'name', //object's "text" property name
    limit: 20, //limit to 20 results
    dropdownPlaceholderText: 'start typing',
    expandInput: true, // expand input
    
    onChange: function(autocomplete, value){
	    mainView.router.loadPage('app_viewlocation.php?slug='+value.slug);
    },
    source: function (autocomplete, query, render) {
        var results = [];
        if (query.length === 0) {
            render(results);
            return;
        }
        // Show Preloader
        autocomplete.showPreloader();
        // Do Ajax request to Autocomplete data
        $$.ajax({
            url: 'http://brewhound.nz/listAjaxLocations.php',
            method: 'GET',
            dataType: 'json',
            //send "query" to server. Useful in case you generate response dynamically
            data: {
                query: query
            },
            success: function (data) {

                // Find matched items
                for (var i = 0; i < data.length; i++) {
	                //console.log(data[i])
                    if (data[i].name.toLowerCase().indexOf(query.toLowerCase()) >= 0) results.push(data[i]);
                }
                // Hide Preoloader
                autocomplete.hidePreloader();
                // Render items by passing array with result items
                render(results);
	            }
	        }); 
	       
	    }
	});


});



$$('.open-preloader').on('click', function () {
    myApp.showPreloader();
    setTimeout(function () {
        myApp.hidePreloader();
    }, 2000);
});
$$('.open-preloader-title').on('click', function () {
    myApp.showPreloader('Custom Title')
    setTimeout(function () {
        myApp.hidePreloader();
    }, 2000);
});

