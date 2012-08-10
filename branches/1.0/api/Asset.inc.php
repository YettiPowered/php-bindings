<?php

/**
 * API for interfacing with Yetti assets over web services.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

class LabelEdAPI_Asset extends LabelEdAPI_BaseAbstract
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
