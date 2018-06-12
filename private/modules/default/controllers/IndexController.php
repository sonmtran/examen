<?php 
class IndexController extends Base_DefaultController
{

    const ID_PRODUCT = 21; // id of product in table marketing_tool.product
	
	/**
	 * @var Model_DBA
	 */
	protected $_mDBAMarketing = null;
	
	
	public function init()
	{
		parent::init();

		$this->_mDBAMarketing = new Model_DBA('marketing');
	}
	
	
	public function indexAction()
	{		
		// create Form
		$oForm = new Default_Form_Index_Register();	
		$oForm->id_center->setMultiOptions($this->getCenterOption());
		$oForm->id_city->setMultiOptions($this->getCityOption());
        $oForm->id_city2->setMultiOptions($this->getCityNoCentreOption());
		$oRequest = $this->getRequest();
		
		// Process form post
		if ($oRequest->isPost())
		{
			switch (true)
			{
				case ! $oForm->isValid($oRequest->getPost()):
					$this->view->ERRORS = $oForm->getMessages();
					break;
					
				case $this->existsEmail($oForm->getValue('email')):
					$this->view->ERRORS = array('email' => array($this->view->translate('exists_email_customer')));
                    //$oForm->id_city->setValue('');
                    $this->view->ERROR_EMAIL = true;
                    $this->view->VAL_CITY = $oForm->getValue('id_city');
                    if($oForm->getValue('id_city') != 0)
                    {
                        $oForm->id_center->setMultiOptions($this->getCenterOption($oForm->getValue('id_city'),false));
                    }

                    break;
					
				default:
					$arrData = $oForm->getValues();
					
					// process date
					list($day, $month, $year) = explode("/", $arrData['dob']);
					$arrData['dob'] = $year ."-". $month ."-". $day;
					//$arrData['register_date'] = date('Y-m-d H:i:s');
					
					// class code
					if ($arrScheduleInfo) {
						$arrData['class_code']    = $arrScheduleInfo['class_code'];
					}
					$arrData['id_program']    = self::ID_PRODUCT;

                    if($arrData['id_city'] == 0)
                    {
                        $arrData['id_city'] = $arrData['id_city2'];
                        unset($arrData['id_city2']);
                    }
                    else
                    {
                        unset($arrData['id_city2']);
                    }

					// add google analytis param
					$this->fillAnalytic($arrData);
					$this->_mDBAMarketing->insert('customer', $arrData);
				    $this->_helper->FlashMessenger('register_ok'); // flash to print ga lead
					$this->_redirect("/" . $this->view->translate('k_success'));
					break;
			}
		}
		else 
		{
			$arrCenter = $this->getCenterByAlias($oRequest->getParam('alias',""));
			if ($arrCenter) {
				$oForm->id_center->setValue($arrCenter['id']);
				$oForm->id_city->setValue($arrCenter['id_city']);
			}
		}
		
		$this->view->FORM = $oForm;
	}
	
	public function successAction()
	{
        $this->view->STATUS = 'success';
        $this->renderScript('index/index.phtml');
	}
	/**
	 * Fill analytic param
	 * 
	 * @param array $arrFormData Form data
	 */
    protected function fillAnalytic(&$arrFormData)
    {
        $arrParam 					 = $this->getAnalyticParam();
        $arrFormData['ga_medium'] 	 = $arrParam['medium'];
        $arrFormData['landing_page'] = $arrParam['landing_page'];
    
        /** Campaign info **/
        $arrCampaign = NULL;
    //var_dump($arrParam);
        if (! empty($arrParam['campaign']))	{
            // get campaign id if have campaign
            $arrCampaign = $this->_getRow(array('name =' => $arrParam['campaign']), 'campaign');
        }
    
        if ($arrParam['medium'] == 'cpc' && empty($arrCampaign))
        {
            // get current campaign if empty campaign
            $arrCampaign = $this->_getRow(array(
                'id_product ='  => self::ID_PRODUCT,
                'date_from <='  => date('Y-m-d'),
                'date_to >=' 	=> date('Y-m-d'),
            ), 'campaign');
        }
    
        if ($arrCampaign) {
            $arrFormData['id_campaign'] = $arrCampaign['id'];
        }
        unset($arrCampaign);
    
    
        /** Media Info **/
        if (! empty($arrParam['source']))
        {
            // get media id if have source
            $arrMedia = $this->_getRow(array('alias =' => $arrParam['source']), 'media');
            	
            if ($arrMedia) {
                $arrFormData['id_media'] = $arrMedia['id'];
            }
        }
    
    }
	
	
	
	/**
	 * Get center option
	 * 
	 * Use for Default_Form_Register
	 * 
	 * @return array
	 */
    protected function getCenterOption($id_city = '',$chosse = true)
    {
        $arrQuery = array(
            'table' => array('a' => 'center', 'b' => 'city'),
            'condition' => array('a.id_city = b.id'),
            'field' => array('id, name, name_vi, id_city', 'name as city_name'),
            'where' => 'a.status = 1',
            'orderby' => 'a.order asc'
        );
        if ($id_city != '')
        {
            $arrQuery['where'] = $arrQuery['where']. ' '. 'AND a.id_city = '.$id_city;
        }
        $rs = $this->_mREFER->select($arrQuery);
        if($chosse)
        {
            $arrOption = array('' => $this->view->translate('ch_center'));
        }


        foreach ($rs['result'] as $item)
        {
            $sCenterName = LANGUAGE == "en" ? Defined_Common::toNonUnicode($item['city_name']) : $item['city_name'];
            $arrOption[$sCenterName][$item['id']] = $item[Defined_Common::getLanguageField('name')];
        }


        return $arrOption;
    }
	
	
	/**
	 * Get Center by Alias
	 * 
	 * @param string $alias Alias of center
	 * @return array
	 */
	protected function getCenterByAlias($alias)
	{
		$rs = $this->_mREFER->select(array(
				'table' => 'center',
				'field' => array('id, id_city'),
				'where' 	=> 'alias = ' . $this->_mREFER->quote($alias),
		));
		
		return current($rs['result']);
	}
	
	
	/**
	 * Get city option
	 * 
	 * Use for Default_Form_Register
	 * 
	 * @return array
	 */
    protected function getCityOption()
    {
        $arrIdCity = $this->_mREFER->select(array(
            'table' 	=> 'center',
            'expr' => array(
                'id_city' => "distinct id_city",
            ),
        ));
        $arrId = array();
        if($arrIdCity['total']>0)
        {
            foreach($arrIdCity['result'] as $k => $v)
            {
                $arrId[]=$v['id_city'];
            }
        }

        if(count($arrId)>0)
        {
            $strId = join(',',$arrId);
        }

        $arrQuery = array(
            'table' 	=> 'city',
            'field' 	=> array('id, name'),
            'orderby' 	=> 'order asc'
        );
        if(!empty($strId))
        {
            $arrQuery['where'] = "id in ({$strId})";
        }
        $rs = $this->_mREFER->select($arrQuery);

        $arrOption = array('' => '--------------------');

        foreach ($rs['result'] as $item)
        {

            $arrOption[$item['id']] = LANGUAGE == "en" ? Defined_Common::toNonUnicode($item['name']) : $item['name'];
        }
        $arrOption[0] = 'Others';

        return $arrOption;
    }

    protected function getCityNoCentreOption()
    {
        $arrIdCity = $this->_mREFER->select(array(
            'table' 	=> 'center',
            'expr' => array(
                'id_city' => "distinct id_city",
            ),
        ));
        $arrId = array();
        if($arrIdCity['total']>0)
        {
            foreach($arrIdCity['result'] as $k => $v)
            {
                $arrId[]=$v['id_city'];
            }
        }

        if(count($arrId)>0)
        {
            $strId = join(',',$arrId);
        }

        $arrQuery = array(
            'table' 	=> 'city',
            'field' 	=> array('id, name'),
            'orderby' 	=> 'order asc'
        );
        if(!empty($strId))
        {
            $arrQuery['where'] = "id not in ({$strId})";
        }
        $rs = $this->_mREFER->select($arrQuery);
        $arrOption = array('' => '--------------------');
        foreach ($rs['result'] as $item)
        {

            $arrOption[$item['id']] = LANGUAGE == "en" ? Defined_Common::toNonUnicode($item['name']) : $item['name'];
        }

        return $arrOption;
    }
	
	
	/**
	 * Check exists email customer in this program
	 * 
	 * @param string $email Email of customer
	 * @return int
	 */
	protected function existsEmail($email)
	{
		$rs = $this->_mDBAMarketing->select(array(
				'table' => 'customer',
				'field' => array('id'),
				'where' => 'id_program = ' . self::ID_PRODUCT . ' and email = ' . $this->_mDBAMarketing->quote($email)
		));
		
		return $rs['total'];
	}
	
	
	/**
     * Get Row from condition
     *
     * @param array $where
     * @param string $table
     * @return array
     */
    protected function _getRow($where, $table = 'customer')
    {
    	$arrayWhere = array();
    	foreach ($where as $field => $val)
    	{
    		$arrayWhere[] = $field . $this->_mDBAMarketing->quote($val);
    	}
    	
    	$rs = $this->_mDBAMarketing->select(array(
    			'table'   => $table,
    			'field'   => array('id'),
    			'where'   => implode(' and ', $arrayWhere),
    			'orderby' => 'id desc',
    	));
    
    	return current($rs['result']);
    }
    public function ajaxCentreAction()
    {
        # only allow ajax request
        if (!$this->getRequest()->isXmlHttpRequest()) {
            die('Access is denied.');
        }

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNorender(true);

        $id_city = $this->_arrParams['id_city'];

        $arrCenter = $this->getCenterOption($id_city);

        $strOption = '';

        foreach ($arrCenter as $k=> $item)
        {
            if($k != '')
            {
                $strOption .= '<optgroup id="id_center-optgroup-'.$k.'" label="'.$k.'">';

                if(is_array($item))
                {
                    foreach ($item as $kv=> $vv)
                    {
                        $strOption.= "<option value=\"{$kv}\">{$vv}</option>";
                    }

                }

                //$strOption.= "<option value=\"0\">Other</option>";
                $strOption .= '</optgroup>';
            }

        }
        if($strOption == '')
        {
            $strOption.= "<option value=\"0\" selected='selected'>&nbsp;</option>";
        }

        echo json_encode($strOption);
    }
}