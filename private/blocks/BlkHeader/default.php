<?php include_once( $_SERVER["DOCUMENT_ROOT"].'/analyticstracking.php'); ?> 
<div id="header" class="header">
    <?php
    	$urlLang = array ();
    	
    	if (Zend_Controller_Front::getInstance ()->getRequest ()->language == 'en') {
    		$urlLang ['en'] = 'javascript:void(0)';
    		$urlLang ['vi'] = 'http://' . substr ( $_SERVER ['SERVER_NAME'], 3 ) . $_SERVER ['REQUEST_URI'];
    	} else {
    		$urlLang ['en'] = 'http://en.' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
    		$urlLang ['vi'] = 'javascript:void(0)';
    	}
    ?>
    
    <script type="text/javascript">

    $(document).ready(function(){
    	common.sLinkRequest = '<?php echo HEADER_LINK;?>';
    	common.sLinkEn = '<?php echo $urlLang['en']?>';
    	common.sLinkVn = '<?php echo $urlLang['vi']?>';
    	common.sClassEn = '<?php echo $lang_code['en']?>';
    	common.sClassVn = '<?php echo $lang_code['vi']?>';
    	common.make_header('<?php echo LANGUAGE ?>');
    	
    });
    
    				
    </script>
	<div class="header_mobile">
		<div class="nav"></div>
	</div>
    <div class="clearfix"></div>
    <div class="header_center">
        <div class="wp_header_center">
            <a href="/" title="Logo" class="logo"><img alt="image" src="<?php echo URL_DEFAULT_IMAGE?>/logo.png"></a>
            <div class="text">
                <p>Boost your <b>success</b> in life.<span> Learn English from the very best</span></p>
               
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    
</div>