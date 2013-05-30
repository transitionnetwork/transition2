Drupal.behaviors.modulename_subidentifier = function(context) { 
	//code goes here
	$('#intronav > li > a').click(function(e){
		var a = $(this);
		var li = a.parent();
		li.parent().children('li').children('ul').hide();
	
		li.children('ul').slideDown(300);
		if(li.children('ul').length() > 0) {
	     	return false;
		}
	});
	
	
}

