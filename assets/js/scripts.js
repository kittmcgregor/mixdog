$(document).ready(function(){

    $("#addAvailList [type=checkbox]").on("change", function(){
        //$("#addAvailList label").addClass('red');
        $(this).parent().parent().parent().submit();
        //console.log($(this).parent().parent().parent());
    });
    
    $( "#tabs" ).tabs();
    	    
});
	
$(document).keyup(function(e) {     
    if(e.keyCode== 27) {
	    $('#quicksearchinput').blur();
	    $('#searchKeywords').blur();
        //alert('Esc key is press');  
    } 
});

$('input#quicksearchinput').focus(
function(){
    $(this).val('');
});

$('#quicksearchinput').autocomplete({
	source: function( request, response ) {
	$('.QSloading').remove();
  	$('#QSform').append('<div class="QSloading"></div>');
		$.ajax({
			url : 'listBrewsMeta.php',
			sortResults: false,
			dataType: "json",
			type: 'Get',
			data: {term: request.term},
		success: function( data ) {
			$('.QSloading').remove();
			//$('#quicksearchinput').removeClass('loading');
			 var array = ( $.map( data, function( item,i ) {
				 //console.log(item.brewtitle);
				 //console.log(item.brewtitle);
				return {
					label: item.brewtitle,
					img: item.brewimg,
					value: i
				}
			}));
		//call the filter here
        response($.ui.autocomplete.filter(array, request.term));
		//console.log(request.term);
		},
		error: function() {
	         $('.searcherror').html('<p>An error has occurred</p>');
	    }
		});
	},
	select: function(event, ui) {  
  	//console.log(ui);
           //location.href=ui.item.value;
    }
    
//}); // remove this to add custom render

})
	$('#quicksearchinput').autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( '<span class="imgwrap">' + "<a href='/" + item.value +"'>" + '<img class="ui-img" src="' + "http://brewhound.nz/assets/images/" + item.img + '"/>' + "</a></span>" )
        .append( '<span class="brewtextwrap">' + " <a href='/" + item.value +"'>" + item.label + '</a></span>' )
        //.append( " <a href='/" + item.value +"'>" + item.label + '</a> <a href="#" class="small-btn remove"><i class="fa fa-times"></i></a>' )
        .appendTo( ul );
    };
   
/*
})
	$('#quicksearchinput').autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.brewtitle + "<br>" + item.brewimg + "</a>" )
        .appendTo( ul );
    };
*/

$('input#searchKeywords').focus(
function(){
    $(this).val('');
});
    
$('#searchKeywords').autocomplete({
	source: function( request, response ) {
	$('#searchKeywords').addClass('loading');
		$.ajax({
			url : 'listBrewsMeta.php',
			sortResults: false,
			dataType: "json",
			type: 'Get',
			data: {term: request.term},
		success: function( data ) {
			$('#searchKeywords').removeClass('loading');
			//$('#quicksearchinput').removeClass('loading');
			 var array = ( $.map( data, function( item,i ) {
				 //console.log(item.brewtitle);
				 //console.log(item.brewtitle);
				return {
					label: item.brewtitle,
					img: item.brewimg,
					value: i
				}
			}));
		//call the filter here
        response($.ui.autocomplete.filter(array, request.term));
		//console.log(request.term);
		},
		error: function() {
	         $('.searcherror').html('<p>An error has occurred</p>');
	    }
		});
	},
	select: function(event, ui) {  
  	//console.log(ui);
           //location.href=ui.item.value;
    }
})
	$('#searchKeywords').autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a href='/" + item.value +"'>" + '<img class="ui-img" src="' + "http://brewhound.nz/assets/images/" + item.img + '"/>' + "</a>" )
        .append( " <a href='/" + item.value +"'>" + item.label + '</a>' )
        //.append( " <a href='/" + item.value +"'>" + item.label + '</a> <a href="#" class="small-btn remove"><i class="fa fa-times"></i></a>' )
        .appendTo( ul );
    };