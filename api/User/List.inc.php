<?php

namespace Yetti\API;

/**
 * User list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class User_List extends ListAbstract
{
	/**
	 * Loads users
	 *
	 * @param int $typeId
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
			
			$item = new User();
			$item->setJson($element);
			
			$return[] = $item;
		}
		
		return $return;
	}
}
