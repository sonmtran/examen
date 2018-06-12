<?php
/**
 * 
 * @author Vu
 * @since 03-2015
 */


class Defined_ReCaptcha
{
    
	static function basic($WordLen = 6,$width = 150,$height = 70,$fontsize = 30)
	{
	    // customize captcha number
	    Zend_Captcha_Word::$CN = Zend_Captcha_Word::$C = Zend_Captcha_Word::$VN = Zend_Captcha_Word::$V = array("0","1","2","3","4","5","6","7","8","9");
	    // delete all image captcha
	    //captcha
	    $captcha = new Zend_Captcha_Image();
	    // Chạy lần đầu
	    // Cài đặt các thông số
	    $captcha->setTimeout('300')
	    ->setWordLen($WordLen)
	    ->setHeight($height)
	    ->setWidth($width)
        ->setDotNoiseLevel(10)
            ->setLineNoiseLevel(1)
	    ->setFontsize('24')
	    ->setImgDir(APPLICATION_PATH.'/../httpdocs/upload/captcha/images/')
	    ->setImgUrl('/upload/captcha/images/')// set đường dẫn cho hình captcha trong <img src="đường_dẫn">
	    ->setFont('./upload/captcha/font/arial.ttf');
	    // Tạo một hình captcha
	    $captcha->generate();
	    // Truyền hình captcha vừa tạo qua View
	    $imageCaptcha = $captcha->render();
	    // Truyền Idcaptcha qua view (để sau khi submit mình lấy Id này để lấy mã xác nhận chứa trong Session)
	    $idCaptcha = $captcha->getId(); // Id này chứa trong hidden field bên View
	    
	    /* Tạo Sesion cho hình captcha vừa tạo.
	     Phương thức $captcha->getId là lấy Id của hình, mà Id của hình thì chính là tên hình
	    */
	    $sessionCaptcha = new Zend_Session_Namespace('Zend_Form_Captcha_'.$captcha->getId());
	    
	    // Truyền giá trị là mã xác nhận vào Session
	    $sessionCaptcha->word = $captcha->getWord(); // $captcha->getWord là lấy mã xác nhận trên hình
	    
	    $html = '
    	        <div id="captcha-element">'.$imageCaptcha.'
                    <input id="keyCaptcha" type="hidden" name="keyCaptcha" value="'.$idCaptcha.'" >
                    <input type="text" name="valueCaptcha" id="valueCaptcha"  class="form-control" placeholder="Nhập mã bảo vệ">
                </div>';
	    return $html;
	}
}


/** End Defined_ReCaptcha **/
/** Location: modules/default/library/Defined/ReCaptcha.php **/