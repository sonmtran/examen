<?php

class ExtensionController extends Base_DefaultController
{

	
	
	
    public function init()
    {
        parent::init();
    }

    
    
    
    public function insertUserChatAction(){
    
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNorender(true);
    	
    	
    	$arrData = $this -> _request ->getParams();
    	$arrInsert = array();
    	$arrInsert['id_city'] = $arrData['id_city'];
    	$arrInsert['id_center'] = $arrData['id_center'];
    	$arrInsert['fullname'] = $arrData['full_name'];
    	$arrInsert['phone'] = $arrData['phone'];
    	$arrInsert['note'] = str_replace("Vui lòng nhập lời nhắn","", $arrData['note']) ;
    	
    	$mMtool = new Model_DBA('marketing');
    	$mMtool->insert('user_chat',$arrInsert);
    	
    	
    
    }
    
    
    
}