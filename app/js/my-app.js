

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
		
		console.log(data);
		
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
		    	console.log($$(this).data('id'));
		    	
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