<?php

/*
  +----------------------------------------------------------------------+
  | (c) Copyright Silkwires. 					                           |
  | 	All Rights Reserved.                                               |
  +----------------------------------------------------------------------+
  | > Module: .........                                                  |
  | > Author: (SW Team)  ........  					                   |
  | >	Class description	: Defined data that is retrieved from database,|
  prepare some methods from base object        |
  +----------------------------------------------------------------------+
 */

class Model_DBA extends Zend_Db_Table {

    private $db = null;

  	function __construct($sDbName='') {
		
    	if (empty ($sDbName)){
    		
    	    $this->db = $this->getDefaultAdapter();
    	}else{
    		/** Auto call from Zend_Registry **/	
	        $this->_setAdapter($sDbName);
	        $this->db =  $this->getAdapter();
        }
        
      
    }
    function __destruct() {

        unset($this->db);
    }

    function getMetaInfo($sTable) {

        return $this->db->describeTable($sTable);
    }

    function insert($table = '', $arrData) {

        $rs = $this->db->insert($table, $arrData);
		if ($rs){
			return $this->db->lastInsertId();
		}

        return $rs;
    }

    function update($sTable = '', $arrData, $strWhere = '') {

        $rs = $this->db->update($sTable, $arrData, $strWhere);

        return $rs;
    }

    function delete($sTable = '', $strWhere = '') {

        $rs = $this->db->delete($sTable, $strWhere);

        return $rs;
    }
    
    function select($arrTable) {

        $select = $this->db->select();

        $select->from ($this->_parseTable($arrTable), $this->_parseColumns($arrTable));

        if (isset ($arrTable['table']['b']) AND isset ($arrTable['condition'])) {

            $sSecondField = isset($arrTable['field'][1]) ? $arrTable['field'][1] : '';
            $select->join(array('b' => $arrTable['table']['b']), $arrTable['condition'][0], Defined_Common::explode(',', $sSecondField) );
            unset($sSecondField);
        }

        if (isset($arrTable['table']['c']) AND isset($arrTable['condition']))
        {
        	$sThirdField = isset($arrTable['field'][2]) ? $arrTable['field'][2] : '';
            $select->join(array('c' => $arrTable['table']['c']), $arrTable['condition'][1], Defined_Common::explode(',', $sThirdField) );
            unset($sThirdField);
        }

        if (isset($arrTable['groupby'])) {
            $select->group($arrTable['groupby']['group']);
            if (isset($arrTable['groupby']['having'])) {
                $select->having($arrTable['groupby']['having']);
            }
        }

        if (isset($arrTable['where']) AND !empty($arrTable['where'])) {
            $select->where($arrTable['where']);
        }
        if (isset($arrTable['orderby']) AND trim($arrTable['orderby']) != '') {
            $select->order($arrTable['orderby']);
        }

        if( isset($arrTable['limit'])){

                $select->limit($arrTable['limit'][0], $arrTable['limit'][1]);
        } 
        
        try{
        $stmt = $select->query();
	    $result = $stmt->fetchAll();
        }
        catch(Exception $ex)
        {
        	echo $select->__toString();
        }

        $rs = array('total' => count($result),
            'result' => $result);
       
        return $rs;
    }

    
    function select_left($arrTable) {
    
    	$select = $this->db->select();
    
    	$select->from ($this->_parseTable($arrTable), $this->_parseColumns($arrTable));
    
    	$i = 0;
    	foreach ($arrTable['table'] as $sAlias => $sTableName) {
    		if ($sAlias == 'a') continue;
    		$sSecondField = isset($arrTable['field'][$i+1]) ? $arrTable['field'][$i+1] : '';
    		$select->joinLeft(array($sAlias => $sTableName), $arrTable['condition'][$i], Defined_Common::explode(',', $sSecondField) );
    		$i++;
    	}
    
    	if (isset($arrTable['groupby'])) {
    		$select->group($arrTable['groupby']['group']);
    		if (isset($arrTable['groupby']['having'])) {
    			$select->having($arrTable['groupby']['having']);
    		}
    	}
    
    	if (isset($arrTable['where']) AND !empty($arrTable['where'])) {
    		$select->where($arrTable['where']);
    	}
    	if (isset($arrTable['orderby']) AND trim($arrTable['orderby']) != '') {
    		$select->order($arrTable['orderby']);
    	}
    
    	if( isset($arrTable['limit'])){
    
    		$select->limit($arrTable['limit'][0], $arrTable['limit'][1]);
    	}
    	#echo $select;die();
    	try{
    	$stmt = $select->query();
    	$result = $stmt->fetchAll();
    	}
    	catch(Exception $ex)
    	{
    	echo $select->__toString();
    }
    
    	$rs = array('total' => count($result),
    	'result' => $result);
    
    	return $rs;
    }
    
    
    function queryPaging($arrTable) {

        $select = $this->db->select();

        $select->from ($this->_parseTable($arrTable), $this->_parseColumns($arrTable));

        if (isset ($arrTable['table']['b']) AND isset ($arrTable['condition'])) {

            $sSecondField = isset($arrTable['field'][1]) ? $arrTable['field'][1] : '';
            $select->join(array('b' => $arrTable['table']['b']), $arrTable['condition'][0], Defined_Common::explode(',', $sSecondField) );
        }

        if (isset($arrTable['table']['c']) AND isset($arrTable['condition'])) {
            $select->join(array('c' => $arrTable['table']['c']), $arrTable['condition'][1], AdminCommon::explode(',', $arrTable['field'][2]) );
        }

        if (isset($arrTable['groupby'])) {
            $select->group($arrTable['groupby']['group']);
            if (isset($arrTable['groupby']['having'])) {
                $select->having($arrTable['groupby']['having']);
            }
        }

        if (isset($arrTable['where']) AND !empty($arrTable['where'])) {
            $select->where($arrTable['where']);
        }
        if (isset($arrTable['orderby']) AND trim($arrTable['orderby']) != '') {
            $select->order($arrTable['orderby']);
        }


        unset($arrTable);
        #echo $select;die();
        return $select;
    }

    private function _parseColumns($arrTable){
        
        # Declare list of columns
        $arrCol = array();

        if (isset($arrTable['field'])) {
            $arrCol = Defined_Common::explode(',', $arrTable['field'][0]);
        }

        # Make a zend db express
        if (isset($arrTable['expr'])) {
            foreach ($arrTable['expr'] as $key => $value) {
                //$arrCol[$key] = $value;
                $arrCol[$key] = new Zend_Db_Expr($value);
            }
        }

        if (count($arrCol) == 0) {
            $arrCol = '*';
        }
        return $arrCol;
    }
    
    private function _parseTable($arrTable){
        if (is_array($arrTable['table']))
            return array('a' => $arrTable['table']['a']);

        return array('a' => $arrTable['table']);    
    }
    
    
    /**
     * Get Database Adapter
     * 
     * @return DefaultAdapter
     * 
     * Date: 17-6-2014
     */
    public function getDbAdapter()
    {
    	return $this->db;
    }
    public function quote($value, $type = null)
    {
        return $this->db->quote($value, $type);
    }
}

?>