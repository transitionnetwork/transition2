Drupal.behaviors.modulename_subidentifier = function(context) { 
	//code goes here
	$('#intronav > li > a').click(function(e){
		var a = $(this);
		var li = a.parent();
		var id = li.attr('class');
		if (li.children('ul').length > 0) {
			li.parent().children('li').each(function(e){
				var this_li = $(this);
				if(this_li.attr('class') != id) {				
					this_li.children('ul.open').hide().removeClass('open');
				}
			});
			li.children('ul').each(function(){
				var ul = $(this);
				if(!ul.hasClass('open')) {
					ul.addClass('open').slideDown(300);	
				}
			});
			return false;
		}
	});
	
	
}