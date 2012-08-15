<?php

namespace Yetti\API;

/**
 * Collection list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Collection_List extends ListAbstract
{
	/**
	 * Loads a list of collections for a given type ID
	 *
	 * @param int $typeId
	 * @param int $page
	 * @return bool
	 */
	public function load($typeId, $page=1)
	{
		$this->webservice()->setRequestPath('/collections/' . $typeId . '.ws');
		$this->webservice()->setRequestParam('page', (int)$page);
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ListAbstract::getItems()
	 */
	public function getItems()
	{
		$return = array();
		foreach ($this->getJson()->listing->items as $item)
		{
			$element = new SimpleXMLElement("<?xml version=\"1.0\"?><yetti><item>" .
			$item->resource->asXml() .
			$item->revision->asXml() .
			"</item></yetti>");
			
			$item = new Collection();
			$item->setJson($element);
			
			$return[] = $item;
		}
		
		return $return;
	}
}
