var common = {
	data:{},
	current_center:'',
	objPopup : null,
	// change dropdown program
	//CONFIG FOR HEADR
	sLinkRequest : '',
	sLinkEn : '',
	sLinkVn : '',
	sClassEn : '',
	sClassVn : '',
	oTrainingFirst: null,
	//CONFIG FOR FOOTER
	sLinkRequestFooter : '',
	close_sc : function(value) {

		var select = jQuery(this).attr('data-id');
		var session = sessionStorage[value];
		//$.session.set(value, 'off');
		sessionStorage[value] = 'off';
		jQuery('.' + value).css('display', 'none');

	},
	make_header : function(lang){
		
		
		var script = document.createElement('script');
		if(lang !="vi"){
			common.sLinkRequest = common.sLinkRequest.replace('http://', 'http://'+lang+".");
		}
		script.src = common.sLinkRequest+"?lang="+lang;

		//inject script tag into head
		document.getElementsByTagName('head')[0].appendChild(script);
		/*var result = "";
		
		$.ajax({
			
		    type: 'POST',
		    url: common.sLinkRequest,
		    crossDomain: true,
		    data: {
		    	format : 'html',
		    	lang : lang
		    	
		    },
		    
		    success: function(responseData, textStatus, jqXHR)
		    {
		    
		        result = responseData;
		    	result = result.replace(/{LINK_EN}/gi, common.sLinkEn);
		    	result = result.replace(/{LINK_VN}/gi, common.sLinkVn);
		    	result = result.replace(/{CLASS_EN}/gi, common.sClassEn);
		    	result = result.replace(/{CLASS_VN}/gi, common.sClassVn);
		    	$("#header").prepend(result);
		    	$('.header_top .wp_menutop a').attr('target','_blank');
		    	$('#menu').slicknav({
		    		label : ''
		    	});
		    	common.center_active();
		    },
		    error: function (ajaxContext) {
		      
		    }

		    
		});*/

		
	},
	
	callback_make_header: function(responseData){
		
		result = responseData.html;
		result = result.replace(/{LINK_EN}/gi, common.sLinkEn);
    	result = result.replace(/{LINK_VN}/gi, common.sLinkVn);
    	result = result.replace(/{CLASS_EN}/gi, common.sClassEn);
    	result = result.replace(/{CLASS_VN}/gi, common.sClassVn);
    	$("#header").prepend(result);
    	$('.header_top .wp_menutop a').attr('target','_blank');
    	$('#menu').slicknav({
    		label : ''
    	});
    	common.current_center = 'center_48';
    	common.center_active();
	},
	
	
	
	
	make_footer : function(lang){
		
		
		var script = document.createElement('script');
		if(lang !="vi"){
			common.sLinkRequestFooter = common.sLinkRequestFooter.replace('http://', 'http://'+lang+".");
		}
		script.src = common.sLinkRequestFooter+"?lang="+lang;
		//inject script tag into head
		document.getElementsByTagName('head')[0].appendChild(script);
		/*var result = "";
		$.ajax({
			
		    type: 'POST',
		    url: common.sLinkRequestFooter,
		    crossDomain: true,
		    data: {
		    	format : 'html',
		    	lang : lang
		    	
		    },
		    success: function(responseData, textStatus, jqXHR)
		    {
		        result = responseData;
		    	result = result.replace(/{GLOBAL}/gi, 'selected-2');
		    	
		    	$("#footer").prepend(result);
		    
		    },
		    
		});*/

		
	},
	
	callback_make_footer: function(responseData){
		
		result = responseData.html;
		result = result.replace(/{EXAM}/gi, 'selected-2');
    	
    	$("#footer").prepend(result);
			
	},
	facebook_like :function(lang){
		
		(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/"+lang+"/sdk.js#xfbml=1&version=v2.0";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
	},
	center_active : function() {

		$(document).ready(function() {

			$("a.center").mouseover(function() {
				var content_show = $(this).attr("data-id");
	
				if(common.current_center != content_show){
					
				$(".actived").removeClass("actived");
				$(this).addClass("actived");
				$(".content_sub").slideUp();
				
				$("#" + content_show).slideDown();
				
				}
				common.current_center = content_show;
				
			});

		});

	},
}