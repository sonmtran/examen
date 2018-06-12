<?php


class Defined_Array2DropDown {
	
	const ARRAY_FORMAT 		= 1;
	const DROPDOWN_FORMAT 	= 2;
	const OPTION_FORMAT 	= 3;
	
	private $arrPrepareDropdown	= array();
	
	public $arrSource 			= array();
	public $setCurrent 			= 0;
	public $arrPersistenceSource= array();
	public $arrNew 				= array();
	public $customizeDropDown 	= array();
	public $infoDropDown 		= array();
	public $bIsRecursive 		= false;
	public $sParentName 		= null;
	public $sLevelName 			= null;
	public $sPrimaryName 		= null;
	public $sFieldName 			= null;
	public $sIconParent			= null;
	public $sIconChild 			= null;
	
	
	public function renderToResult( $iFormat ){
		
		$this -> arrangeArray();
		
		switch ($iFormat) {
			
			case self::ARRAY_FORMAT :
				
				return $this->arrNew;
				break;
		
			case self::DROPDOWN_FORMAT  :
				
				return $this->toDropDownList();
				break;
			
			case self::OPTION_FORMAT:
				return $this->toOptionList();
				
			default:
				break;
		}
		
		return array();
	}
	
	private function plainArray(){
		
		
		/** Reset new array **/
		$this->arrNew = array();
		
		/** Move id to key **/
		foreach ( $this->arrSource as $arrVal){
			
				$this->arrNew[ $arrVal[ $this->sPrimaryName ] ] = $arrVal ;
		}
		/** Keep this array for parsing to dropdown **/
		$this -> arrPrepareDropdown = $this -> arrNew;
	}			
	
	private function recursiveArray($iCurrParent=0, $iCurrLevel=1){
		
		foreach ( $this->arrSource as $iKey => $arrVal){
			
			if( $arrVal[ $this->sParentName ] == $iCurrParent){
				
				$arrVal[ $this->sLevelName ] = $iCurrLevel;
				$this->arrNew[] = $arrVal;
				unset($this->arrSource[$iKey]);
				$this->recursiveArray($arrVal[ $this->sPrimaryName ], $iCurrLevel + 1);
			}
		}			
	}
	
	private function arrangeArray(  ){

		
		if($this -> bIsRecursive){
			
			$this -> recursiveArray();
			$this -> _toRecursiveArray();
			
		}else{
			
			$this -> plainArray();
			$this -> _toPlainArray();
		}
		
	}
	
	private function toDropDownList(){
		
		$sHTML = '<div class="select1"><select ';
		
   		foreach ($this->infoDropDown as $sKey => $sVal) {
   			
   			$sHTML .= $sKey . '=' . '"' . $sVal . '"';
   		}
   		
		$sHTML .= '>';
		$sHTML .= '<option value="all">All</option>	';
		
		if( count( $this->arrPersistenceSource) > 0 ){
			
			foreach ($this->arrPersistenceSource as $iId => $sContent) {
				
				$sHTML .= '<option value="' . $iId . '">' . $sContent . '</option>	';
				
			}
			
		}else{
		
			foreach ($this -> arrPrepareDropdown as $iId => $arrRow) {
				
				
				
			
				$sSelected = (intval ($this->setCurrent) > 0 ) ? 'selected' : '';
				$sContent = ( $this->_isCustomize() ) ? $arrRow[ $this -> sFieldName ] . $this->customizeDropDown[0] . $arrRow[ $this->customizeDropDown[1] ] : 
													  	$arrRow[ $this -> sFieldName ];
				if($iId == $this->setCurrent)
					$sHTML .= '<option value="' . $iId . '" ' . $sSelected . '>' . $sContent . '</option>	';
				else 
					$sHTML .= '<option value="' . $iId . '" >' . $sContent . '</option>	';
			}
	
		}
		
	
		$sHTML .= '</select></div>';
					
	   	return $sHTML;		
	}
	
	
	private function toOptionList()
	{
		$sHTML = '';
	
		if (count($this->arrPersistenceSource) > 0)
		{
			foreach ($this->arrPersistenceSource as $iId => $sContent)
			{
				$sHTML .= '<option value="' . $iId . '">' . $sContent . '</option>';
			}
		}
		else
		{
			foreach ($this->arrPrepareDropdown as $iId => $arrRow)
			{		
				$sSelected = (intval ($this->setCurrent) > 0 ) ? 'selected' : '';
				$sContent  = ( $this->_isCustomize() ) ? $arrRow[ $this -> sFieldName ] . $this->customizeDropDown[0] . $arrRow[ $this->customizeDropDown[1] ] : $arrRow[ $this -> sFieldName ];
				
				if ($iId == $this->setCurrent)
					$sHTML .= '<option value="' . $iId . '" ' . $sSelected . '>' . $sContent . '</option>	';
				else
					$sHTML .= '<option value="' . $iId . '" >' . $sContent . '</option>	';
			}
		}
			
		return $sHTML;
	}
	
	
	private function _isCustomize(){
		
		return isset($this->customizeDropDown[1]) AND !is_null($this->customizeDropDown[1]);		
	}
	
	private function _toPlainArray(){
		
		$arrNew = array();
		
		foreach ( $this->arrNew as $iId => $arrVal){
			
			$arrNew[ $iId ] = $arrVal[$this -> sFieldName] ;
		}		
		
		$this->arrNew =  $arrNew;
	}
	
	private function _toRecursiveArray() {
		
   		$arrResult = array();
   		
	   	foreach ($this -> arrNew as $arrVal){
	   		
	   		$iLevel 	= $arrVal[ $this -> sLevelName ];
	   		$iId 		= $arrVal[ $this -> sPrimaryName ];
	   		
	   		if( $iLevel == 1 ){
	   			
	   			$arrVal[ $this -> sFieldName ] = $this -> sIconParent . $arrVal[ $this -> sFieldName ]; 
	   		
	   		}else{
	   			
	   			$arrVal[ $this -> sFieldName ] = str_repeat( $this -> sIconChild, $iLevel ) . $arrVal[ $this -> sFieldName ];
	   			
	   		}
	   		/** If we don't need to customize the display, 
	   		 *  only get value of new array instead of entire array **/
	   		$arrResult[ $iId ] = ($this->_isCustomize()) ? $arrVal : $arrVal[ $this -> sFieldName ];
   			
	   	}
	   	
	   	/** Reset new array **/
	   	$this -> arrNew = $arrResult;	
		/** Keep this array for parsing to dropdown **/
		$this -> arrPrepareDropdown = $this -> arrNew;	   		
	}
	
	function __construct(){ 
		
 		/** Force to create object **/
		$this -> sParentName 	= 'parent_id';
		$this -> sLevelName 	= 'level';
		$this -> sPrimaryName 	= 'id';
		$this -> sFieldName 	= 'name';
		$this -> sIconParent 	= '+ ';
		$this -> sIconChild 	= '---';
 		
 	}
 	
 		
}

?>