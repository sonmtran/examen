<?php
class Block_BlkWhy extends Zend_view_Helper_Abstract {
	
    public function BlkWhy() {
        # To able to call model
        # To do some statement
        # To assign some variable to default.php
        $iNumCenter = $this ->numCenter();
        require ('BlkWhy/default.php');    	
    }
    
    
    
    
    public function numCenter(){
    	
    	$mREFER = new Model_DBA('reference');
    	$arrResult = $mREFER -> getDbAdapter() ->select() -> from('center',array('id'))->where('status=1')->query() ->fetchAll();
    	unset($mREFER);
		return count($arrResult);
    	
    	
    }
}
?>
