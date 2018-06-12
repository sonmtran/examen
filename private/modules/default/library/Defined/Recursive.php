<?php
class Defined_Recursive {
	public $arrOrg = array ();
	public $arrNew = array ();
	public $sParentName = null;
	public $sLevelName = null;
	public $sPrimaryName = null;
	public $sFieldName = null;
	public $sIconParent = null;
	public $sIconChild = null;
	
	
	
	// menu
	public $arrMenu = array();
	public $sKey = '';
	public $sMenu = null;
	public $sParentTag = null;
	public $sParentTagEnd = null;
	public $sChildTag = null;
	public $sChildTagEnd = null;
	public $iIdselect = 0;
	public $sClassselect = 0;
	public $sHasChildClass = null;
	private function arrangeArray($iCurrParent = 0, $iCurrLevel = 1) {

		foreach ( $this->arrOrg as $iKey => $arrVal){
				
			if( $arrVal[ $this->sParentName ] == $iCurrParent){
		
				$arrVal[ $this->sLevelName ] = $iCurrLevel;
				$this->arrNew[] = $arrVal;
				unset($this->arrOrg[$iKey]);
		
				$this->arrangeArray($arrVal[ $this->sPrimaryName ], $iCurrLevel + 1);
			}
		}
	}
	
	
	public function renderMenu ($iCurrParent = 0, $iCurrLevel = 1)
	{
		// check have children
		$hasChild = false;
		if ($iCurrParent != 0) {
			$this->sMenu .= $this->sParentTag;
		}
		foreach ( $this->arrMenu as $iKey => $arrVal ) {
				
			if ($arrVal [$this->sParentName] == $iCurrParent) {
				$hasChild = true;
				unset ( $this->arrMenu [$iKey] );
				$temp = str_replace ( array (
						"{id}",
						"{name}",
						"{select}"
				), array (
						$arrVal [$this->sKey],
						$arrVal [$this->sFieldName],
						$arrVal [$this->sPrimaryName] == $this->iIdselect ? $this->sClassselect : ""
				), $this->sChildTag );
				$this -> sMenu .= $temp;
		
		
				if ($this->renderMenu ( $arrVal [$this->sPrimaryName], $iCurrLevel + 1 )) {
		
					$temp2 =  str_replace ( '{haschild}', $this -> sHasChildClass, $temp);
					$this ->sMenu =  str_replace ( $temp,$temp2, $this -> sMenu );
						
						
				} else {
		
					$temp2 =  str_replace ( '{haschild}', "", $temp);
					$this -> sMenu = str_replace ( $this->sParentTag . $this->sParentTagEnd, "", $this -> sMenu );
					$this -> sMenu = str_replace ( $temp, $temp2, $this -> sMenu );
						
				}
		
				$this->sMenu .= $this->sChildTagEnd;
			}
		}
		if ($iCurrParent != 0) {
			$this->sMenu .= $this->sParentTagEnd;
		}
		return $hasChild;
		
	}
	
	
	public function toArray() {
		$this->arrangeArray ();
		
		$arrResult = array ();
		
		foreach ( $this->arrNew as $arrVal ) {
			
			$sContent = $arrVal [$this->sFieldName];
			$iLevel = $arrVal [$this->sLevelName];
			$iId = $arrVal [$this->sKey];
			
			if ($iLevel == 1) {
				
				$arrResult [$iId] = $this->sIconParent . $sContent;
			} else {
				
				$arrResult [$iId] = str_repeat ( $this->sIconChild, $iLevel ) . $sContent;
			}
		}
		
		return $arrResult;
	}
	function __construct() {
		
		/**
		 * Force to create object *
		 */
		
		$this->sParentName = 'id_parent';
		$this->sLevelName = 'level';
		$this->sPrimaryName = 'id';
		$this->sFieldName = 'name_vi';
		$this->sIconParent = '+ ';
		$this->sIconChild = '---';
		$this->sMenu = "";
		$this->sParentTag = "<ul>";
		$this->sParentTagEnd = "</ul>";
		$this->sChildTag = "<li>";
		$this->sChildTagEnd = "</li>";
		$this->iIdselect = 0;
		$this->sClassselect = "active";
	}
}

?>