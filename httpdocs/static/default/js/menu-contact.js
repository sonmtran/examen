(function($){
	
	MenuContact = function($ele, option)
	{
		var defaults = {
			active_class: 'ct_selected',
			sub_lv: 'ul'
		};
		var  configs = $.extend({}, defaults, option);
		
		this.init = function()
		{
			$ele.click(function(){
				manager($(this).parent());
				return false;
			});
		};
		
		manager = function(obj)
		{
			hideAll(obj);
			
			if (obj.find(configs.sub_lv).is(":visible") == true)
				return;

			show(obj);
		};
		
		hideAll = function(not_obj)
		{			
			$ele.parent().find(configs.sub_lv).not(not_obj).each(function(){
				$(this).slideUp();
				$(this).parent().removeClass(configs.active_class);
			});
		};
		
		show = function(obj)
		{
			obj.addClass(configs.active_class);
			obj.find(configs.sub_lv).slideDown( "slow" );
		};
		
		/** Init **/
		this.init();
	}
	
	
	$.fn.MenuContact = function(options)
	{
		new MenuContact($(this), options);
	}
	
	$(document).ready(function(){
		$('#menu_contact .sub_lv1 > a').MenuContact();
	});
})(jQuery);