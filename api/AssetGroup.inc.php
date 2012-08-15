<?php

require_once 'ListAbstract.inc.php';
require_once 'Asset.inc.php';

/**
 * API for interfacing with LabelEd property over web services.
 *
 * $Id$
 */

class AssetGroup extends ListAbstract
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
			$asset = new Asset();
			$asset->setJson($json);
			
			$assets[] = $asset;
		}
		
		return $assets;
	}
}
