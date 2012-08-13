<?php

require_once 'ListAbstract.inc.php';
require_once 'Language.inc.php';

/**
 * API for interfacing with a list of languages over web services.
 *
 * $Id$
 */

class LabelEdAPI_Languages extends LabelEdAPI_ListAbstract
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
	 * @see LabelEdAPI_ListAbstract::getItems()
	 */
	public function getItems()
	{
		$return = array();
		
		foreach ($this->getJson()->languages as $json)
		{
			$item = new LabelEdAPI_Language();
			$item->setJson($json);
			$return[] = $item;
		}
		
		return $return;
	}
}
