var PopupTeacher = {
	
	configs: {
		e_close:'#popup_close',
		bg_div:'#bg_PopUp',
		pop_div:'#popupTeacher',
		ct_div:'#popup_content',
	},
	
	manager: function()
	{
		var obj = this;
		
		jQuery(this.configs.bg_div).click(function() {
			obj.close();
		});
		
		jQuery(this.configs.e_close).click(function() {
			obj.close();
			return false;
		});
	},
	
	loadPopup: function()
	{
		jQuery(this.configs.bg_div).fadeIn("slow");
		jQuery(this.configs.pop_div).fadeIn("slow");
		this.centerPopup();
	},
	
	show: function(url)
	{
		var obj = this;
		jQuery(this.configs.ct_div).load(url, function(){
			obj.loadPopup();
		});
	},
	
	close: function()
	{
		jQuery(this.configs.bg_div).fadeOut("slow");
		jQuery(this.configs.pop_div).fadeOut("slow");
	},
	
	centerPopup: function()
	{
		var windowWidth = document.documentElement.clientWidth;
		var windowHeight = document.documentElement.clientHeight;
		var popupHeight = jQuery(this.configs.pop_div).height();
		var popupWidth = jQuery(this.configs.pop_div).width();
		jQuery(this.configs.pop_div).css({
			"position": "fixed",
			"top": windowHeight / 2 - popupHeight / 2,
			"left": windowWidth / 2 - popupWidth / 2
		});
		jQuery(this.configs.bg_div).css({
			"height": windowHeight
		})
	},
}
