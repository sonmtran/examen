<?php

class Defined_Language {

    private $sLangKey;
    private $oTranslate;
    private $oLocation;
    private $_arrConfigure;
    static $oSingleTon;

    function __destruct() {
        unset($this->sLangKey);
        unset($this->oTranslate);
        unset($this->oLocation);
    }

    function __construct() {
        
    }

    function setOption($arrConfigure)
    {
    	$this -> _arrConfigure = $arrConfigure;
    }
    
    public function load() 
    {
        if (is_null($this->oTranslate)) 
        {
            $this->install();
        }
      
        Zend_Registry::set('Zend_Translate', $this->oTranslate);
        
       
       
    }

    static function getInstant() 
    {
        
        if (!isset(self::$oSingleTon)) 
        {
            $c = __CLASS__;
            self::$oSingleTon = new $c();
            unset($c);
        }
        return self::$oSingleTon;
    }

    public function getLanguageKey() 
    {
        return $this->sLangKey;
    }


    public function install($sLang = '') 
    {
    	
        # find a change lang request from client
        if (trim($sLang) != '') 
        {
        	/* Overwrite language key from request */
            $this->setLanguage($sLang);
        } 
        else 
        {
            $this->getLanguage();
        }
        unset($sLang);
        
        $this->initZendTranslate();
        # load language from Zend and return object language
        $this->callZendTranslate();
    }

    private function initZendTranslate() 
    {
    	
        $this->oLocation    = new Zend_Locale($this->sLangKey);
        
        $this->oTranslate   = new Zend_Translate(
                array('adapter' => 'ini', 'locale'  => $this->sLangKey, 'content' => $this -> _arrConfigure['path'], 'scan' => Zend_Translate::LOCALE_FILENAME)
        );
        
        
        
    }

    private function callZendTranslate() 
    {

        $this->oLocation->setLocale($this->sLangKey);
        
        Zend_Registry::set('Zend_Locale', $this->oLocation);
		
        $this->oTranslate->setLocale($this->oLocation);
        
        if (!$this->oTranslate->isAvailable($this->oLocation->getLanguage())) 
        {
            $this->oTranslate->setLocale($this->sLangKey);
        }
    }

    private function setLanguage($sLang) 
    {
        setcookie('lang_index', $sLang, time() + 30 * 24 * 60 * 60, '/');
        
        $this->sLangKey = $sLang;
        #var_dump(1,$this->sLangKey);
        unset($sLang);
    }

    private function getLanguage() 
    {
    	
		if (isset($_COOKIE["lang_index"])) 
        {
        	$this->sLangKey = $_COOKIE["lang_index"];
        }
        else 
        {
        	/* Initialize language key from configure  */
        	$this->sLangKey = $this -> _arrConfigure['default'];
        }
        
    }

}

?>