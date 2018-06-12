<?php

class Defined_Common {

    static function createItemsPerPage($iCurrent = 0) {

        $iMax = 30;
        $sHTML = '<select class="select1" id="cboLimit">';

        for ($i = 5; $i <= $iMax; $i+=5) {
            $sSelected = ($i == $iCurrent) ? 'selected' : '';
            $sHTML .= '<option ' . $sSelected . '>' . $i . '</option>	';
        }
        $sHTML .= '</select>';

        unset($iMax);
        return $sHTML;
    }
    
    static function getArrayValue($array, $key, $default = null)
    {
    	if (isset($array[$key])) 
    		return $array[$key];
    	
    	return $default;
    }
    
    static function getCityName($name)
    {
    	if (LANGUAGE=="en"){
    		return self::toNonUnicode($name);
    	}
    	return $name;
    } 
    
    
    static function detectAlias($sFieldName) {

        $arrTmp = explode('.', $sFieldName);
        if (count($arrTmp) > 1) {
            return $arrTmp;
        }
        # Field name is not include alias table
        return array('', $sFieldName);
    }

    static function format_date($date) {
    	if ($date == "0000-00-00 00:00:00" || $date == "0000-00-00") {
    		return null;
    	}
    	$oDateTemp = new DateTime ( $date );
    	return $oDateTemp->format ( 'd' ) . "/" . $oDateTemp->format ( 'm' ) . "/" . $oDateTemp->format ( 'Y' );
    }
    static function format_date_month($date) {
    	if ($date == "0000-00-00 00:00:00" || $date == "0000-00-00") {
    		return null;
    	}
    	$oDateTemp = new DateTime ( $date );
    	return $oDateTemp->format ( 'd' ) . "/" . $oDateTemp->format ( 'm' ) . "/" . $oDateTemp->format ( 'Y' );
    }
    
    static function format_date_time($date) {
    	if ($date == "0000-00-00 00:00:00" || $date == "0000-00-00") {
    		return null;
    	}
    	$oDateTemp = new DateTime ( $date );
    	if(($oDateTemp->format('h') == '12') && ($oDateTemp->format('i')=="00") && $oDateTemp->format("A") =="AM"){
    		return null;
    	}
    	return $oDateTemp->format('h').":".$oDateTemp->format('i')." ".$oDateTemp->format("A");
    }
    static function getAge($tBirthday) {

    	if($tBirthday=="0000-00-00 00:00:00"){return null;}
        $bday = new DateTime($tBirthday);
       
        $today = new DateTime(date('F.j.Y', time())); // for testing purposes
        $diff = $today->diff($bday);
        return $diff->y;
    }

    static function explode($sDelimiter, $sText) {
        $arrNew = explode($sDelimiter, $sText);
        $arrResult = array();
        foreach ($arrNew as $val) {
            $arrResult[] = trim($val);
        }

        unset($arrNew);
        return $arrResult;
    }

    static function loadView($sPath = '') {
        $view = new Zend_View();
        $view->setScriptPath($sPath);
        unset($sPath);

        return $view;
    }
    
    
    //get item from array by input attr
    //
    static function getByAttr($arrInput,$sAttr,$sValue)
    {
    	
    	if(count($arrInput)>0)
    	{
    		foreach($arrInput as $arrItem)
    		{
    			if($arrItem[$sAttr]== $sValue)
    			{
    				unset($arrInput,$sAttr,$sValue);
    				return $arrItem;
    			}
    		}
    	}
    	else
    	{
    		return array();
    	}
    	
    }
    
    //filter array by attr
    //
    static function filterByAttr($arrInput,$sAttr,$sValue)
    {
    	$result = array();
    	if(count($arrInput)>0)
    	{
    		foreach($arrInput as $index => $arrItem)
    		{
    			if($arrItem[$sAttr]== $sValue)
    			{
    				
    				$result[$index] = $arrItem;
    				
    			}
    		}
    		return $result;
    	}
    	else
    	{
    		return array();
    	}
    	 
    }
    
    static function toNonUnicode($string){
    	if ($string) {
    		$arrUnicodeChars = array(
    				"ạ", "á", "à", "ả", "ã", "Ạ", "Á", "À", "Ả", "Ã",
    				"â", "ậ", "ấ", "ầ", "ẩ", "ẫ", "Â", "Ậ", "Ấ", "Ầ", "Ẩ", "Ẫ",
    				"ă", "ặ", "ắ", "ằ", "ẳ", "ẫ", "ẵ", "Ă", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ",
    				"ê", "ẹ", "é", "è", "ẻ", "ẽ", "Ê", "Ẹ", "É", "È", "Ẻ", "Ẽ",
    				"ế", "ề", "ể", "ễ", "ệ", "Ế", "Ề", "Ể", "Ễ", "Ệ",
    				"ọ", "ộ", "ổ", "ỗ", "ố", "ồ", "Ọ", "Ộ", "Ổ", "Ỗ", "Ố", "Ồ", "Ô", "ô",
    				"ó", "ò", "ỏ", "õ", "Ó", "Ò", "Ỏ", "Õ",
    				"ơ", "ợ", "ớ", "ờ", "ở", "ỡ",
    				"Ơ", "Ợ", "Ớ", "Ờ", "Ở", "Ỡ",
    				"ụ", "ư", "ứ", "ừ", "ử", "ữ", "ự", "Ụ", "Ư", "Ứ", "Ừ", "Ử", "Ữ", "Ự",
    				"ú", "ù", "ủ", "ũ", "Ú", "Ù", "Ủ", "Ũ",
    				"ị", "í", "ì", "ỉ", "ĩ", "Ị", "Í", "Ì", "Ỉ", "Ĩ",
    				"ỵ", "ý", "ỳ", "ỷ", "ỹ", "Ỵ", "Ý", "Ỳ", "Ỷ", "Ỹ",
    				"đ", "Đ"
    		);
    		$arrNonUnicodeChars = array(
    				"a", "a", "a", "a", "a", "A", "A", "A", "A", "A",
    				"a", "a", "a", "a", "a", "a", "A", "A", "A", "A", "A", "A",
    				"a", "a", "a", "a", "a", "a", "a", "A", "A", "A", "A", "A", "A",
    				"e", "e", "e", "e", "e", "e", "E", "E", "E", "E", "E", "E",
    				"e", "e", "e", "e", "e", "E", "E", "E", "E", "E",
    				"o", "o", "o", "o", "o", "o", "O", "O", "O", "O", "O", "O", "O", "o",
    				"o", "o", "o", "o", "O", "O", "O", "O",
    				"o", "o", "o", "o", "o", "o",
    				"O", "O", "O", "O", "O", "O",
    				"u", "u", "u", "u", "u", "u", "u", "U", "U", "U", "U", "U", "U", "U",
    				"u", "u", "u", "u", "U", "U", "U", "U",
    				"i", "i", "i", "i", "i", "I", "I", "I", "I", "I",
    				"y", "y", "y", "y", "y", "Y", "Y", "Y", "Y", "Y",
    				"d", "D"
    		);
    		$arrSpecialChars = array(
    				'!', '"', '#', '$', '%', '&',
    				"'", '(', ')', '*', '+', ',',
    				'-', '.', '/', ':', ';', '<',
    				'=', '>', '?', '@', '[', '\\',
    				']', '^', '_', '`', '{', '|',
    				'}', '~'
    		);
    		 
    		// Convert unicode->abc
    		$string = str_replace($arrUnicodeChars, $arrNonUnicodeChars, $string);
    		// Bỏ khỏang trắng hai đầu
    		$string = trim($string);
    		return $string;
    	}
    	return '';
    	 
    	 
    }
    
    

static function convertAlias($string) {
		if ($string) {
			$arrUnicodeChars = array (
					"ạ",
					"á",
					"à",
					"ả",
					"ã",
					"Ạ",
					"Á",
					"À",
					"Ả",
					"Ã",
					"â",
					"ậ",
					"ấ",
					"ầ",
					"ẩ",
					"ẫ",
					"Â",
					"Ậ",
					"Ấ",
					"Ầ",
					"Ẩ",
					"Ẫ",
					"ă",
					"ặ",
					"ắ",
					"ằ",
					"ẳ",
					"ẫ",
					"ẵ",
					"Ă",
					"Ắ",
					"Ằ",
					"Ẳ",
					"Ẵ",
					"Ặ",
					"ê",
					"ẹ",
					"é",
					"è",
					"ẻ",
					"ẽ",
					"Ê",
					"Ẹ",
					"É",
					"È",
					"Ẻ",
					"Ẽ",
					"ế",
					"ề",
					"ể",
					"ễ",
					"ệ",
					"Ế",
					"Ề",
					"Ể",
					"Ễ",
					"Ệ",
					"ọ",
					"ộ",
					"ổ",
					"ỗ",
					"ố",
					"ồ",
					"Ọ",
					"Ộ",
					"Ổ",
					"Ỗ",
					"Ố",
					"Ồ",
					"Ô",
					"ô",
					"ó",
					"ò",
					"ỏ",
					"õ",
					"Ó",
					"Ò",
					"Ỏ",
					"Õ",
					"ơ",
					"ợ",
					"ớ",
					"ờ",
					"ở",
					"ỡ",
					"Ơ",
					"Ợ",
					"Ớ",
					"Ờ",
					"Ở",
					"Ỡ",
					"ụ",
					"ư",
					"ứ",
					"ừ",
					"ử",
					"ữ",
					"ự",
					"Ụ",
					"Ư",
					"Ứ",
					"Ừ",
					"Ử",
					"Ữ",
					"Ự",
					"ú",
					"ù",
					"ủ",
					"ũ",
					"Ú",
					"Ù",
					"Ủ",
					"Ũ",
					"ị",
					"í",
					"ì",
					"ỉ",
					"ĩ",
					"Ị",
					"Í",
					"Ì",
					"Ỉ",
					"Ĩ",
					"ỵ",
					"ý",
					"ỳ",
					"ỷ",
					"ỹ",
					"Ỵ",
					"Ý",
					"Ỳ",
					"Ỷ",
					"Ỹ",
					"đ",
					"Đ" 
			);
			$arrNonUnicodeChars = array (
					"a",
					"a",
					"a",
					"a",
					"a",
					"A",
					"A",
					"A",
					"A",
					"A",
					"a",
					"a",
					"a",
					"a",
					"a",
					"a",
					"A",
					"A",
					"A",
					"A",
					"A",
					"A",
					"a",
					"a",
					"a",
					"a",
					"a",
					"a",
					"a",
					"A",
					"A",
					"A",
					"A",
					"A",
					"A",
					"e",
					"e",
					"e",
					"e",
					"e",
					"e",
					"E",
					"E",
					"E",
					"E",
					"E",
					"E",
					"e",
					"e",
					"e",
					"e",
					"e",
					"E",
					"E",
					"E",
					"E",
					"E",
					"o",
					"o",
					"o",
					"o",
					"o",
					"o",
					"O",
					"O",
					"O",
					"O",
					"O",
					"O",
					"O",
					"o",
					"o",
					"o",
					"o",
					"o",
					"O",
					"O",
					"O",
					"O",
					"o",
					"o",
					"o",
					"o",
					"o",
					"o",
					"O",
					"O",
					"O",
					"O",
					"O",
					"O",
					"u",
					"u",
					"u",
					"u",
					"u",
					"u",
					"u",
					"U",
					"U",
					"U",
					"U",
					"U",
					"U",
					"U",
					"u",
					"u",
					"u",
					"u",
					"U",
					"U",
					"U",
					"U",
					"i",
					"i",
					"i",
					"i",
					"i",
					"I",
					"I",
					"I",
					"I",
					"I",
					"y",
					"y",
					"y",
					"y",
					"y",
					"Y",
					"Y",
					"Y",
					"Y",
					"Y",
					"d",
					"D" 
			);
			$arrSpecialChars = array (
					'!',
					'"',
					'#',
					'$',
					'%',
					'&',
					"'",
					'(',
					')',
					'*',
					'+',
					',',
					'-',
					'.',
					'/',
					':',
					';',
					'<',
					'=',
					'>',
					'?',
					'@',
					'[',
					'\\',
					']',
					'^',
					'_',
					'`',
					'{',
					'|',
					'}',
					'~' 
			);
			
			// Convert unicode->abc
			$string = str_replace ( $arrUnicodeChars, $arrNonUnicodeChars, $string );
			// Remove Non ASCII Characters
			$string = preg_replace ( "/[^(\x20-\x7F)]*/", '', $string );
			// Convert Special characters to space
			// Convert Special characters to space
			$string = str_replace ( $arrSpecialChars, ' ', $string );
			
			// Convert many spaces to space
			$string = preg_replace ( "/\s+/", ' ', $string );
			// Bỏ khỏang trắng hai đầu
			$string = trim ( $string );
			// Tạo kết nối giữa các từ bằng -
			$string = str_replace ( ' ', '-', $string );
			
			// Chuyển thành chữ thường
			$string = strtolower ( $string );
			
			return $string;
		}
		return '';
	}
    /**
     * Combine 2 array that relative together by master key and slave key
     * To do: 
     * 		- Moving value of master key into index of array master
     * 		Combine items that same foreign key become array
     * 		- Put slave array to master array
     *
     * @param Array $arrMaster
     * @param Array $arrSlave
     * @param String $sPrimaryKey
     * @param String $sForeignKey
     * @return Array
     */
	
	
    static function joinArray($arrMaster, $arrSlave, $sPrimaryKey, $sForeignKey) {

        if (count($arrMaster) == 0 OR count($arrSlave) == 0)
            return $arrMaster;

        $arrSlaveNew = $arrMasterNew = array();
        foreach ($arrSlave as $iIndex => $arrValue) {
            /** Restructure array slave 
             * 	Combine items that same foreign key become array
             * * */
            $foreignValue = $arrValue [$sForeignKey];
            $arrSlaveNew [$foreignValue] [] = $arrValue;
        }

        foreach ($arrMaster as $iIndex => $arrValue) {
            /** Moving value of primary key into index of array master * */
            /** Take value of primary key * */
            $iIndex = $arrValue [$sPrimaryKey];
            /** Calling array same foreign key * */
            $arrSlaveSame = (isset($arrSlaveNew[$iIndex])) ? $arrSlaveNew[$iIndex] : array();
            /** Put array same foreign key to array master * */
            $arrValue['sub'] = $arrSlaveSame;
            /** Put value of primary key into index of array master* */
            $arrMasterNew[$iIndex] = $arrValue;
        }

        return $arrMasterNew;
    }
    
 static  function substrwords($text, $maxchar, $end='...') {
    	
    	 if(strlen($text) >0 )
    	 {
    	 	if (strlen($text) > $maxchar ) {
    	 		$words = preg_split('/\s/', $text);
    	 		$output = '';
    	 		$i      = 0;
    	 		while (1) {
    	 			$length = strlen($output)+strlen($words[$i]);
    	 			if ($length > $maxchar) {
    	 				break;
    	 			}
    	 			else {
    	 				$output .= " " . $words[$i];
    	 				++$i;
    	 			}
    	 		}
    	 		$output .= $end;
    	 	}
    	 	 
    	 	else {
    	 		$output = $text;
    	 	}
    	 	
    	 } 
    	 else
    	 {
    	 	return "";
    	 }
    	return $output;
    }



    static function Strip_text($data,$size,$elipse = true){
    	$data = strip_tags($data);
    	if(mb_strlen($data, 'utf-8') > $size){
    		$result = mb_substr($data,0,mb_strpos($data,' ',$size,'utf-8'),'utf-8');
    		if(mb_strlen($result, 'utf-8') <= 0){
    			$result = mb_substr($data,0,$size,'utf-8');
    			$result = mb_substr($result, 0, mb_strrpos($result, ' ','utf-8'),'utf-8');;
    		}
    		if($elipse) {
    			$result .= "...";
    		}
    	}else{
    		$result = $data;
    	}
    	return $result;
    }
    
    static function wp_trim_words($text, $num_words = 55, $more = null) {
    	if (null === $more)
    		$more = "...";
    	$original_text = $text;
    	$words_array = preg_split ( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
    	$sep = ' ';
    	if (count ( $words_array ) > $num_words) {
    		array_pop ( $words_array );
    		$text = implode ( $sep, $words_array );
    		$text = $text . $more;
    	} else {
    		$text = implode ( $sep, $words_array );
    	}
    	return $text;
    }
    
   static function getLanguageField($field ,$default= null)
   {
   	$code = is_null($default)?LANGUAGE:$default;
   	
   	if($code=="en")return $field;
   	else
   	{
   		return $field.'_'.LANGUAGE;
   	}
   	
   } 
    
   static  function getImageUrl($sInput=null,$sSize=null)
    {
    	if(is_null($sInput) || is_null($sSize))
    	{
    		return "";
    	}
    	try{
    	return URL_UPLOAD.$sSize.str_replace(REPLACE_UPLOAD, "", $sInput);
    	}
    	catch (Exception $e )
    	{
    		return "";
    	}
    	
    	
    	
    }
    

}

?>
