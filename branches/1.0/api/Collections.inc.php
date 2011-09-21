<?php
require_once 'ListAbstract.inc.php';
require_once 'Collection.inc.php';
/**
 * API for interfacing with a list of LabelEd items over web services.
 *
 * $Id$
 */

class LabelEdAPI_Collections extends LabelEdAPI_ListAbstract
{
	/**
	 * Loads items by collection type ID
	 *
	 * @param int $typeId
	 * @return bool
	 */
	public function load($typeId, $page=1)
	{
		$this->webservice()->setRequestPath('/collections/' . $typeId . '.ws');
		$this->webservice()->setRequestParam('page', (int)$page);
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
			$element = new SimpleXMLElement("<?xml version=\"1.0\"?><labeled><item>" .
			$item->resource->asXml() .
			$item->revision->asXml() .
			"</item></labeled>");
			
			$item = new LabelEdAPI_Collection();
			$item->setXml($element);
			
			$return[] = $item;
		}
		
		return $return;
	}
}
