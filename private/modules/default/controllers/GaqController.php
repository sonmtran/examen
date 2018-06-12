<?php
/**
 * Get Google analytic param.
 * 
 * How to use: Add javascript code before tag </body>
 * 
 *		<script type="text/javascript">
 *			$(document).ready(function(){var curSearch=window.location.search.substring(1);if(curSearch!='' || document.referrer != ''){$.post('/gaq',curSearch+'&referrer='+decodeURIComponent(document.referrer));}});
 *		</script>
 * 
 * @author Dai
 * @since 16/01/2015
 */

class GaqController extends Zend_Controller_Action
{
	public function indexAction()
	{
		// disable layout and render
		$this->_helper->viewRenderer->setNoRender(true);
		
		// Not Ajax request
		if (! $this->getRequest()->isXmlHttpRequest())
		{
			echo 'Access is denied.';
			exit();
		}
		
		
		$param 	= $this->getRequest()->getParams(); // param via javascript past into
		$host 	= $this->getRequest()->getHttpHost();
		$seo 	= '/^(http:\/\/|https:\/\/)?(www\.)?google(\.com?)?(\.[a-z]{2})?(.*)?$/';
		$adw 	= '/^(http:\/\/|https:\/\/)?(www\.)?doubleclick\.net(.*)?$/i';
		$direct = '/^(http:\/\/|https:\/\/)?(www\.)?'.$host.'(.*)?$/';
		$seo_bing  = '/^(http:\/\/|https:\/\/)?(www\.)?bing(\.com?)?(\.[a-z]{2})?(.*)?$/';
		$seo_yahoo = '/^(http:\/\/|https:\/\/)?(www\.)?([a-z]{2}\.)?yahoo\.com(.*)?$/';
		
		$campaign = '';
		$source   = '';
		$medium   = '';
		
		if (! empty($param['utm_source']))
		{
			// request param from url
			$source   = $param['utm_source'];
			$campaign = isset($param['utm_campaign']) ? $param['utm_campaign'] : '';
			$medium   = isset($param['utm_medium']) ? $param['utm_medium'] : '';
		}
		else
		{
			if (! empty($param['referrer']) && ! preg_match($direct, $param['referrer']))
			{
				$campaign = ''; // current campaign
		
				if (! empty($param['gclid'])
				&& ( preg_match($seo, $param['referrer']) // referrer from google
						OR preg_match($adw, $param['referrer']) ) // referrer from doubleclick.net
				)
				{
					$source = 'google';
					$medium = 'cpc'; // google adword
				}
				elseif(preg_match($seo, $param['referrer']) || preg_match($seo_bing, $param['referrer']) || preg_match($seo_yahoo, $param['referrer']))
				{
					$source = parse_url($param['referrer']);
					$source = $source['host'];
					$medium = 'organic'; // SEO
				}
				else 
				{
					$source = parse_url($param['referrer']);
					$source = $source['host'];
					$medium = 'referral'; // Reference
				}
			}
			else
			{
				// khong luu session
				return;
				$campaign = ''; //currentCampaign
				$source   = '';
				$medium   = '(none)'; // Access direct
			}
		}
		
		$arrGA = array(
				'campaign' => $campaign,
				'source'   => $source,
				'medium'   => $medium
		);
		
		// luu session
		$oSession = new Zend_Session_Namespace('gaq');
		$oSession->_gaq = serialize($arrGA);
	}
}