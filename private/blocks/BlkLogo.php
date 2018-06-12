<?php
class Block_BlkLogo extends Zend_View_Helper_Abstract
{
    public function BlkLogo()
    {
        # To able to call model
        # To do some statement
        # To assign some variable to default.php
        
        $get_action =  Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        if($get_action!="program")
        {
        	require ('BlkLogo/default.php');
        }
    }
    
}