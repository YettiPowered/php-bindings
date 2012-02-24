<?php
require_once 'ListAbstract.inc.php';
require_once 'Item.inc.php';
/**
 * API for interfacing with a list of LabelEd items over web services.
 *
 * $Id$
 */

class LabelEdAPI_Items extends LabelEdAPI_ListAbstract
{
	/**
	 * Loads items by item type class ID
	 *
	 * @param int $typeClassId
	 * @return bool
	 */
	public function load($typeClassId, $page=1)
	{
		$this->webservice()->setRequestPath('/items/' . $typeClassId . '.ws');
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
			$element = new SimpleXMLElement("<?xml version=\"1.0\"?><yetti><item>" .
			$item->resource->asXml() .
			$item->revision->asXml() .
			"</item></yetti>");
			
			$item = new LabelEdAPI_Item();
			$item->setXml($element);
			
			$return[] = $item;
		}
		
		return $return;
	}
}
