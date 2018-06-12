<?php


class Base_DefaultController extends Zend_Controller_Action
{

	const sModuleName 	= 'default';
	
    protected $arrConfigSite;	
    protected $arrConfigModule;
    protected $_arrParams;	
    protected $_sActionUrl;	
    protected $_bIsPost;
    protected $_mDBA;
    protected $_mREFER;
    protected $_oSession;
    protected $_lang;
  

    public function init(){
    	
		parent::init();
		$this->loadTemplate('layout');
		//$this->getLang();
	}

	protected function loadTemplate($sTemplate){
		
		# Create shotcut to array paramemters
        $this->_arrParams = $this->_request->getParams();
        
        # Check post method
        $this->_bIsPost = $this->_request->isPost();
        
        # default namespace
        $this->_oSession = new Zend_Session_Namespace(); 
        
        # Launch data layer for Corporate db
        $this->_mREFER    	 = new Model_DBA();
		$this -> _sActionUrl = '/' . $this->_arrParams['module'] . '/' . $this->_arrParams['controller'] .'/' . $this->_arrParams['action'];
		
		# Reset previous data if exists
		$this->view->headTitle()->set('');
		$this->view->headMeta()->getContainer()->exchangeArray(array());
		$this->view->headLink()->getContainer()->exchangeArray(array());
		$this->view->headScript()->getContainer()->exchangeArray(array());
		
		# Declare module path
		$sModulePath = APPLICATION_PATH . '/modules/' . self::sModuleName;
		
        # Another modules must use the view/scripts of default module
        # We include it here
        if ( $this->getRequest()->getModuleName() != self::sModuleName){
            $this->view->addScriptPath( $sModulePath . '/views/scripts/');
        }
        
		# Launch libray to module
		set_include_path($sModulePath . '/library' . PATH_SEPARATOR . get_include_path());
		
		# Launch configure to module
		set_include_path($sModulePath . '/configs' . PATH_SEPARATOR . get_include_path());
		include_once 'define.php';
		
		# Launch main configure
		$oBootstrap =  Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$this->arrConfigSite = $oBootstrap->getOption('config_site');
		
		# Launch admin config site
		$oConfig 		= new Zend_Config_Ini('application.ini');
		$oListConfig 	= $oConfig->get(APPLICATION_ENV);
		$this -> arrConfigModule = $oListConfig->config_site->toArray();
		Zend_Registry::set('config_module', $this -> arrConfigModule);
		
		# Define parameter from configure
		define('DOMAIN_CMS', 	$this -> arrConfigModule['domainCMS']);
		
		# Define Head office id from configure
		define('HEAD_OFFICE', 	$this -> arrConfigModule['headOffice']);
		
		# Define Teacher training id from configure
		define('TEACHER_TRAINING', 	$this -> arrConfigModule['teacherTraining']);
		
		# Define google map api from configure
		define ( 'GMAP_API', $this->arrConfigModule ['googlemap_api'] );

		
		# Define header link from configure
		define('HEADER_LINK', 	$this -> arrConfigModule['header_link']);
		
		# Define footer link from configure
		define('FOOTER_LINK', 	$this -> arrConfigModule['footer_link']);
		
		# Define  link from configure
		$this -> view -> EX_LINK = $this -> arrConfigModule['ex_link'];
	
		# Launch admin config layout
		$oListConfig = $oConfig->get($sTemplate);
		$arrConfigLayout = $oListConfig->toArray();
		
		# Load current language
        $oLang = Defined_Language::getInstant();
        define('LANGUAGE', 	$oLang->getLanguageKey());
                
		# Launch title to layout
		if (isset ($arrConfigLayout['title']))
			$this->view->headTitle($arrConfigLayout['title']);
		
		# Launch meta to layout
		if (isset($arrConfigLayout['metaHttp']) AND count($arrConfigLayout['metaHttp'])>0){
			
			foreach($arrConfigLayout['metaHttp'] as $key => $value){
				$tmp = explode("|", $value);
				$this->view->headMeta()->appendHttpEquiv($tmp[0], $tmp[1]);
			}
		}
		
		if (isset($arrConfigLayout['metaName']) AND count($arrConfigLayout['metaName'])>0){
			foreach($arrConfigLayout['metaName'] as $key => $value){
				$tmp = explode("|", $value);
				$this->view->headMeta()->appendName($tmp[0], $tmp[1]);
			}
		}

		# Launch css to layout
		if (isset($arrConfigLayout['fileCss']) AND count($arrConfigLayout['fileCss'])>0){
			foreach($arrConfigLayout['fileCss'] as $key => $css){
				$this->view->headLink()->appendStylesheet(URL_DEFAULT_CSS . $css,'screen');
			}
		}
		
		# Launch js to layout
		if (isset($arrConfigLayout['fileJs']) AND count($arrConfigLayout['fileJs'])>0){
			foreach($arrConfigLayout['fileJs'] as $key => $js){
				$this->view->headScript()->appendFile(URL_DEFAULT_JS . $js,'text/javascript');
			}
		}
		$option = array('layoutPath'=>$sModulePath . '/layouts/scripts' , 'layout'=>$sTemplate);
		Zend_Layout::startMvc($option);
		
		#set meta keywords
		$this -> setMeta('keywords', $this -> view -> translate('keywords'));
	}
	
	protected function setMeta($sKey, $sContent)
	{
		$this->view->headMeta()->setName($sKey, $sContent);
	}
	
	protected function setTitle($sContent)
	{
		$this->view->headTitle()->set($sContent);
	}
	public function preDispatch()
	{
	    $this->_gaq();
	}
	
	
	/**
	 * Detect langding page
	 */
	protected function _gaq()
	{
	    if ($this->getRequest()->isXmlHttpRequest())
	        return;
	
	    $flag = false;
	    $sLandingPage = $this->getRequest()->getRequestUri();
	    // filter google analytic param
	    $sLandingPage = preg_replace('/utm_campaign(=([^\&]*))?\&?/', '', $sLandingPage);
	    $sLandingPage = preg_replace('/utm_source(=([^\&]*))?\&?/', '', $sLandingPage);
	    $sLandingPage = preg_replace('/utm_medium(=([^\&]*))?\&?/', '', $sLandingPage);
	    $sLandingPage = preg_replace('/gclid=([^\&]*)\&?/', '', $sLandingPage);
	
	    if ($sLandingPage != $this->getRequest()->getRequestUri()) {
	        $flag = true;
	        $sLandingPage = preg_replace('/[\?\&]$/', '', $sLandingPage);
	    }
	
	    
	    $oSession = new Zend_Session_Namespace('gaq');
	
	    if (! isset($oSession->landing_page) || $flag)
	    {
	        /*if (preg_match('/^(.+)\/$/', $sLandingPage, $arrMath))
	         {
	         $oSession->landing_page = $arrMath[1];
	         }
	         else*/
	        {
	            $oSession->landing_page = $sLandingPage;
	        }
	
	    }
	}
	
	
	/**
	 * Get Analytic Param
	 *
	 * @return array
	 */
	protected function getAnalyticParam()
	{
	    $oSession = new Zend_Session_Namespace('gaq');
	
	    if (! empty($oSession->_gaq))
	    {
	        $arrGA = unserialize($oSession->_gaq);
	    }
	    else
	    {
	        $arrGA = array(
	            'campaign' => '',
	            'source'   => '',
	            'medium'   => '(none)'
	        );
	    }
	
	    $arrGA['landing_page'] = $oSession->landing_page;
	
	    return $arrGA;
	}

	
}