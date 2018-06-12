<div class="blk-chat box_pp">
	<script type="text/javascript" src="<?php echo URL_DEFAULT_JS ?>/livechat.js"></script>
	<div id="blur" style="display: none; width: 100%;height: 100%;position: fixed; z-index: 1000; top: 0; left: 0; background-color: #000;opacity: 0.8;"></div>

    <div style="display: none;position: fixed;z-index: 1001; top: 100px; left: 50%;" id="lz_request_window" class="">
    	<div class="wp-live-chat">
    		<div class="info-live-chat">
            	<h2>HỖ TRỢ TRỰC TUYẾN</h2>
                <p><b>Thứ Hai - Thứ Sáu</b></p>
				<p><b>Sáng:</b> 09:00 - 12:00	   <b> Chiều:</b> 13:00 - 19:00</p>
				<p style="color:#5c5c5c; font-size:14px;">Tư vấn học tiếng Anh cùng ILA</p>
            </div>

            <form style="padding:0px;margin:0px;" target="lz_chat_frame.3.2" action="http://onlinesupport.ilavietnam.com/chat.php?template=lz_chat_frame.3.2.chat&acid=69c4&intgroup=SUxBLUN1c3RvbWVyQ2FyZQ__&hg=P0lMQVN1cHBvcnQ,SUxBLVNBTEUtU1VQUE9SVD9JTEEtVEVTVE1BU1RFUi1TVVBQT1JUP09TQy1IYU5vaTE,T1NDLUhvQ2hpTWluaDE,SVQtaGVscGRlc2s_" method="post" name="lz_login_form">

    	<div class="frm-live-chat">
    		<p class="txt-wellcome">Xin chào bạn !</p>
            <p style="margin:6px 0">Vui lòng chọn trung tâm ILA và điền thông tin bên dưới<br /> để được tư vấn trực tuyến</p>
            <input style="width: 60%; float: left;" type="text" onblur="if(this.value=='')this.value='Họ và tên';" onclick="if(this.value=='Họ và tên')this.value='';" value="Họ và tên" id="lz_invitation_name" class="txt-input-row not-null" name="full_name">
			<div>
            	<input style="float: right; width: 38%;" type="text" onblur="if(this.value=='')this.value='Điện thoại';" onclick="if(this.value=='Điện thoại')this.value='';" value="Điện thoại" id="lz_invitation_phone"  class="txt-input-row not-null" name="phone">
            	<div class="clear"></div>
            </div> 	
            
            <div style="margin-bottom: 4px;">
			
			
			<select id="lz_invitation_city"  class="lz_input_groups select-chat lz_chat_form_groups not-null" style="float: left;width: 40%" name="id_city">
			
				<option value="">- Chọn Thành phố - </option>
				<option value="48">Hồ Chí Minh</option>
				<option value="20">Hà Nội</option>
				<option value="1">Đà Nẵng</option>
				<option value="50">Bình Dương</option>
				<option value="49">Biên Hòa</option>
				<option value="70">Vũng Tàu</option>
				
				
            </select>  
            
            <select  id="lz_chat_form_groups" class="lz_input_groups select-chat not-null" name="intgroup" style="float: right;width: 58%">
				<option value="">- Chọn Trung tâm - </option>
				<option data-id="48" data-center="1" value="FO-HCM1">ILA Nguyễn Đình Chiểu</option>
				<option data-id="48" data-center="4" value="FO-HCM3">ILA Nguyễn Cư Trinh</option>
				<option data-id="48" data-center="9" value="FO-HCM5">ILA Hùng Vương</option>
				<option data-id="48" data-center="22" value="FO-HCM17">ILA Phú Lâm </option>
	
				<option data-id="48" data-center="13" value="FO-HCM11">ILA Phú Mỹ Hưng</option>
				
				<option data-id="48" data-center="" value="FO-HCM9">ILA The Manor</option>
				
				<option data-id="48" data-center="10" value="FO-HCM8">ILA Phan Xích Long</option>
				
				<option data-id="48" data-center="12" value="FO-HCM10">ILA Lý Thường Kiệt</option>
				
				<option data-id="20" data-center="16" value="FO-TC-HN1">ILA Cầu Giấy</option>
				
				<option data-id="20" data-center="17" value="FO-TC-HN2">ILA Tây Sơn</option>
				
				<option data-id="1" data-center="19" value="FO-TC-DN1">ILA Đà Nẵng</option>
				
				<option data-id="50" data-center="15" value="FO-BD1">ILA Bình Dương</option>
				
				<option data-id="49" data-center="20" value="FO-BH1">ILA Biên Hòa</option>
				
				<option data-id="70" data-center="18" value="FO-VTC1">ILA Vũng Tàu</option>
				
            </select>  
				<div class="clear"></div>
			</div>
            	
            <input type="text" onblur="if(this.value=='')this.value='Vui lòng nhập lời nhắn';" onclick="if(this.value=='Vui lòng nhập lời nhắn')this.value='';" value="Vui lòng nhập lời nhắn" id="lz_invitation_question" class="txt-input-row" name="note"> 
            
            <div class="bnt-send-chat"><a onclick="send_invi();" class="send_invi">BẮT ĐẦU CHAT</a></div>
            <div class="livebox-close" onclick="lz_livebox_close();">&nbsp;</div>
            </div>
            </form>
        </div>

	</div>
	<div class="chart-with-us" style="display: none;" data-id="box-chart-with-us">
		<div class="close_s close_chat"></div>
		<a href="#">Chat with us!</a>
	</div>
</div>            

<script>
$(document).on('change',"select[name='id_city']",function(){
	var id = $(this).val();
	$("select[name='intgroup'] option").not("[value=0]").hide();
	$("select[name='intgroup'] option[data-id='"+id+"']").show();
	if(id==0){
		$("select[name='intgroup'] option").not("[value=0]").show();
	}
	
});


</script>