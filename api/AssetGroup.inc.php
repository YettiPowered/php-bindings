<?php

require_once 'ListAbstract.inc.php';
require_once 'Asset.inc.php';

/**
 * API for interfacing with LabelEd property over web services.
 *
 * $Id$
 */

class LabelEdAPI_AssetGroup extends LabelEdAPI_ListAbstract
{
	/**
	 * Returns an array of assets in this group
	 * 
	 * @return array
	 */
	public function getItems()
	{
		$assets = array();
		
		foreach ($this->getJson() as $json)
		{
			$asset = new LabelEdAPI_Asset();
			$asset->setJson($json);
			
			$assets[] = $asset;
		}
		
		return $assets;
	}
}
