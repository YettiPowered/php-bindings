<?php

namespace Yetti\API;

/**
 * Collection filter model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Collection_Filter extends Item_Filter
{
	/**
	 * (non-PHPdoc)
	 * @see Yetti\API.Item_Filter::getUriBase()
	 */
	protected function getUriBase()
	{
		return 'collections';
	}
}
