<?php

namespace Yetti\API;

/**
 * Collection list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Collection_List extends Resource_ListAbstract
{
	/**
	 * Construct a new collection list model
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setPath('collections');
	}
	
	/**
	 * Returns a new collection object
	 * 
	 * @return object \Yetti\API\Collection
	 */
	protected function getNewItemObject()
	{
		return new \Yetti\API\Collection();
	}
}
