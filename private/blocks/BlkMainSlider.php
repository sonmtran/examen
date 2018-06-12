

<?php
class Block_BlkMainSlider extends Zend_View_Helper_Abstract
{
    public function BlkMainSlider()
    {
    	$show = array(
    			'award',
    			'teacher',
    			'index',
    			'newevent',
    			'quality',
    			'program',
    	);
    	$get_action =  Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    	if(in_array($get_action, $show))
    	{
    		 require ('BlkMainSlider/default.php');
    	}
       
    }
    
}