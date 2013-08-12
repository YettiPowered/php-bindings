<?php

namespace Yetti\API;

/**
 * Order status model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Order_Status extends BaseAbstract
{
	/**
	 * Returns the status ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Returns the name of this status
	 * 
	 * @return string
	 */
	public function getName()
	{
		return (string)$this->getJson()->name;
	}
}
