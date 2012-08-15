<?php

namespace Yetti\API;

/**
 * Model represents a single asset (attached to a resource)
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

class Asset extends BaseAbstract
{
	/**
	 * Returns the language ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return $this->getJson()->item->resourceId;
	}
	
	/**
	 * Returns the asset alt text
	 * 
	 * @return string
	 */
	public function getAltText()
	{
		return $this->getJson()->altText;
	}
	
	/**
	 * Return the asset link URL
	 * 
	 * @return string
	 */
	public function getUrl()
	{
		return $this->getJson()->url;
	}
}
