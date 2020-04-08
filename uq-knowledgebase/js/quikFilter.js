/**
*	Tom's Basic jQuery Filtering
*	- looks for an event param called search which defines
*		the attribute it's going to search for
*	- bind this event to a search field with a value
*/

var quikFilter = function(event){
	var search_value = $(this).attr('value').toLowerCase();
	// Find all matching suburbs and their ids

	$("["+event.data.search+"]").each(function(){
		var this_element = $(this).attr(event.data.search).toLowerCase();
		if(this_element.indexOf(search_value)>=0){
			$(this).show();
		}else{
			$(this).hide();
		}
	});
};

var advFilter = function(event){
	//var search_value = $(this).attr('value').toLowerCase();
	var search_value = $(this).val().toLowerCase();

	var filter_type = $(event.data.btngroup+" > .active").attr('value');
	//var filter_type = "tag";
	$('.' + filter_type).each(function(){
		var this_element = $(this).text().toLowerCase();
		if(this_element.indexOf(search_value)>=0){
			$(this).parent().show();
		}else{
            console.log("not matching");
			$(this).parent().hide();
		}
	});
};
