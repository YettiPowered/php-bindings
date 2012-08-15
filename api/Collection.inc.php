<?php

namespace Yetti\API;

/**
 * Individual collection model
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

class Collection extends Resource_Abstract
{
	/**
	 * Returns a singular name for this type of resource
	 * 
	 * @return string
	 */
	protected function getSingularName()
	{
		return 'collection';
	}
		
	/**
	 * Sets the parent ID for the collection
	 * 
	 * @param $parentId
	 * @return void
	 */
	public function setParentId($parentId)
	{
		$this->getJson()->item->parentId = (int)$parentId;
	}
}
