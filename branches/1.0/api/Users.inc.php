<?php
require_once 'ListAbstract.inc.php';
require_once 'User.inc.php';
/**
 * API for interfacing with a list of LabelEd items over web services.
 *
 * $Id$
 */

class LabelEdAPI_Users extends LabelEdAPI_ListAbstract
{
	/**
	 * Loads users
	 *
	 * @param int $page
	 * @return bool
	 */
	public function load($typeId=null, $page=1)
	{
		$this->webservice()->setRequestPath('/users.ws');
		$this->webservice()->setRequestParam('page', (int)$page);
		if ($typeId) {
			$this->webservice()->setRequestParam('typeId', (int)$typeId);
		}
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setXml($this->webservice()->getResponseXmlObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see LabelEdAPI_ListAbstract::getItems()
	 */
	public function getItems()
	{
		$return = array();
		foreach ($this->getXml()->listing->items as $item)
		{
			$element = new SimpleXMLElement("<?xml version=\"1.0\"?><yetti><item>" .
			$item->resource->asXml() .
			$item->revision->asXml() .
			"</item></yetti>");
			
			$item = new LabelEdAPI_User();
			$item->setXml($element);
			
			$return[] = $item;
		}
		
		return $return;
	}
}
