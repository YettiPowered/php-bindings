<?php

namespace Yetti\API;

/**
 * Collection filter type model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Collection_Filter_Type extends Item_Filter_Type
{
	/**
	 * (non-PHPdoc)
	 * @see Yetti\API.Item_Filter_Type::getUriBase()
	 */
	protected function getUriBase()
	{
		return 'collections';
	}
}
