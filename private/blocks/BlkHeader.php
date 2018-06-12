
<?php
class Block_BlkHeader extends Zend_View_Helper_Abstract
{
	private $mDBA;
	private $mREFER;
	private $arrTemp;
	private $arrCount;
	
    public function BlkHeader()
    {
        # To able to call model
        # To do some statement
        # To assign some variable to default.php
    	
    	$objParams  		= 	Zend_Controller_Front::getInstance()->getRequest();
    	$key 				=	$objParams -> getControllerName();
    	$arrCurrent = array(
    			'index'=>'',
    			'program'=>'',
    			'quality'=>'',
    			'new-event'=>'',
    			'center'=>'',
    			'award'=>'',
    			'about'=>''
    		     
    			
    			
    			);
    	
    	if($key=="mission" || $key == "award" ||$key =="testimonials" || $key == "teacher")
    	{
    		$key = "about";
    	}
    		$arrCurrent [$key] 	= 	'selected';
    	
    	
    	
    	
    	
    	$this -> mREFER = new Model_DBA('reference');
    	$this -> mDBA = new Model_DBA();
    	//array quality for
    	//$arrQuanlity = $this -> getQuality();
    	
    	//$arrCity 			= 	$this -> City();
    	//$arrCenter 			= 	$this -> Center();
    	//$arrOthers 			=	$this -> Others();
    	//$listCity			= 	$this -> listcity($arrCity, $arrCenter);	    	
    	//$listCenter 		=	$this -> listcenter($this -> arrTemp, $this -> arrCount);
    	$lang_code = array(
    	'vi'=>'',
    	'en'=>'',
    	'fr'=>''				
    	); 
    	
    	
         $lang_code[LANGUAGE] = "active";
       	
    	
        require ('BlkHeader/default.php');
    }
    
    
    private function getQuality(){

    	$arrTable = array (
    			'table' => array (
    					'a' => 'quality'
    			),
    			'field' => array (
    					'a.*'
    			),
    			'where' => " a.status = '1'",
    			'orderby' => 'a.order asc'
    	);
    	$arrQua = $this-> mDBA->select ( $arrTable );    	
    	/* $oRecursive = new Defined_Recursive();
    	$oRecursive ->arrMenu = $arrQua['result'];
    	$oRecursive -> sParentName = "id_parent";
    	$oRecursive -> sFieldName = Defined_Common::getLanguageField('name');
    	$oRecursive -> sPrimaryName = "id";
    	$oRecursive -> sKey         = 'alias';
    	$oRecursive -> sLevelName = "level";
    	$oRecursive -> sHasChildClass = "sub_child";
    	$oRecursive ->sParentTag = "<ul class='submenu_child'>";
    	$oRecursive -> sChildTag = "<li class='{haschild}' > <a  title='{name}'  href='/quality/details/alias/{id}'>{name}</a>";
    	//render option
		$oRecursive -> renderMenu();
    	$result =  $oRecursive ->sMenu; */
    	return $arrQua['result'];
    	
    	 
    }
    private function City()
    {
    	$arrQuery 	= array (
						
					'table' 	=> array( 'a' => 'center', 'b' => 'city'),
					'field'		=> array( 'b.id, b.name,b.alias,b.code ' ),
					'condition' => array( 'a.id_city = b.id'),
					'orderby' 	=> 'b.order ASC',
    				'groupby'	=> array( 'group' => 'id_city' ));
    	
    	$rs 		= $this -> mREFER -> select ($arrQuery);
    	return $rs['result'];
    }
    
    private function Programmes()
    {
    	$arrQuery 	= array (
    
    			'table' 	=> array( 'a' => 'gallery_program'),
    			'field'		=> array( 'a.*' ),
    			'orderby' 	=> 'order ASC',);
    	 
    	$rs 		= $this -> mREFER -> select ($arrQuery);
    	return $rs['result'];
    }
    
    private function Others()
    {
    	$arrQuery 	= array (
    
    			'table' 	=> array( 'a' => 'gallery_program'),
    			'field'		=> array( 'a.*' ),
    			'orderby' 	=> 'order ASC',
    			'where' => "status = '1' AND type = '1'"
    			);
    
    	$rs 		=  $this -> mDBA -> select ($arrQuery);
    	return $rs['result'];
    }
    private function Center()
    {
    	$arrQuery 	= array (
    
    			'table' 	=> array( 'a' => 'center'),
    			'field'		=> array( 'a.id, a.id_city, a.name, a.address, a.name_vi, a.address_vi' ));
    	 
    	$rs 		= $this -> mREFER -> select ($arrQuery);
    	return $rs['result'];
    }
    
    private function listcity($arrCity, $arrCenter)
    {
    	$strCity = "";
    	$arrTemp [][] = null;
    	$arrCount [] = null;
    	$i = 0;
    	foreach ($arrCity as $city)
    	{
    		$strCity .= '<a class="center';
    	
    		if($i == 0) $strCity .= ' actived';
    			
    		$strCity .= '" href="/center/index/city/'.$city['id'].'" data-id=center_'.$i.' title="'.$city['name'].'">';
    		if(LANGUAGE=="en")
    			$strCity .= Defined_Common::convertAlias($city['name']);
    		else 
    			$strCity .= $city['name'];
    		$strCity .= '</a>';
    		$j=0;
    		foreach ($arrCenter as $center)
    		{
    			if ($center['id_city'] == $city['id'])
    			{
    				$arrTemp[$city['id']][$center['id']] = $center;
    				$j++;
    			}
    		}
    		$i++;
    		$arrCount[$city['id']] = $j;
    	}
    	$this -> arrTemp = $arrTemp;
    	$this -> arrCount = $arrCount;
    	
    	return $strCity;
    }
    
    private function listcenter($arrTemp, $arrCount)
    {
    	$strCenter = "";
    	$i = 0;
    	unset($arrTemp[0]);
    	unset($arrCount[0]);
    	foreach ($arrTemp as $temp)
    	{
    		$strCenter .= '<div ';
    	
    		if ($i == 0) $strCenter .= 'style="overflow: hidden; display: block;" ';
    		else $strCenter .= 'style="overflow: hidden; display: none;" ';
    			
    		$strCenter .= 'class="content_sub" id="center_' . $i .'">';
    		$strCenter .= '<div class="sub_hcm">';
    		 
    		$j = 0;
    		foreach ($temp as $center)
    		{
    			$divided = $arrCount[$center['id_city']] / 3;
    			$count = ceil($divided);
    			
    			if ($arrCount[$center['id_city']] % 3 == 0)
    			{
    				$end = ($count * 3 - 1);
    			} else {
    				$floor = floor($divided) + 0.5;
    				
    				if ($divided < $floor)
    					$end = ($count * 3 - 3);
    				else 
    					$end = ($count * 3 - 2);
    			}
    			 
    			if ($j == 0)
    			{
    				$strCenter .= '<div class="f"><ul class="list_sub">';
    			}
    			elseif ($j == $count)
    			{
    				$strCenter .= '<div class="s"><ul class="list_sub">';
    			}
    			elseif ($j == ($count * 2))
    			{
    				$strCenter .= '<div class="l"><ul class="list_sub">';
    			}
    			$strCenter .= '<li>
		        		<a href="/center/details/center/'.$center['id'].'" title="">'. $center[Defined_Common::getLanguageField('name')] .'
		                	<span>'. $center[Defined_Common::getLanguageField('address')] .'</span>
		                </a>
		       		</li>';
    			 
    			if($j == ($count - 1) || $j == ($count * 2 - 1) || $j == $end)
    				$strCenter .= '</ul></div>';
    			$j++;
    		}
    		 
    		$strCenter .= '</div>';
    		$strCenter .= '</div>';
    		$i++;
    	}
    	
    	return $strCenter; 
    }
    
}