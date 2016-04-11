$(document).ready(function(){
    $("#addAvailList [type=checkbox]").on("change", function(){
        //$("#addAvailList label").addClass('red');
        $(this).parent().parent().parent().submit();
        //console.log($(this).parent().parent().parent());
    });
    
    $( "#tabs" ).tabs();
    	    
});