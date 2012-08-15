<?php

namespace Yetti\API;

/**
 * Abstract class for list models
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

abstract class ListAbstract extends BaseAbstract
{
	/**
	 * Returns the total number of items avaliable for this listing
	 * 
	 * @return int
	 */
	public function getTotalItemCount()
	{
		return (int)$this->getJson()->listing->totalItems;
	}
	
	/**
	 * Returns the total number of pages available in this listing
	 * 
	 * @return int
	 */
	public function getTotalPages()
	{
		return (int)$this->getJson()->listing->totalPages;
	}
	
	/**
	 * Returns the currently loaded page number
	 * 
	 * @return int
	 */
	public function getCurrentPage()
	{
		return (int)$this->getJson()->listing->currentPage;
	}
	
	/**
	 * Returns an array of ResourceAbstract objects from the listing
	 * 
	 * @return array
	 */
	abstract public function getItems();
}
