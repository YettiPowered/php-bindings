<?php

namespace Yetti\API;

/**
 * Language list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Language_List extends ListAbstract
{
	/**
	 * Loads a list of available languages
	 *
	 * @return bool
	 */
	public function load()
	{
		$this->webservice()->setRequestPath('/languages.ws');
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
		
		foreach ($this->getJson()->languages as $json)
		{
			$item = new Language();
			$item->setJson($json);
			$return[] = $item;
		}
		
		return $return;
	}
}
