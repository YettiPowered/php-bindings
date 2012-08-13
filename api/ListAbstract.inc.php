<?php
require_once 'BaseAbstract.inc.php';

/**
 * API for interfacing with LabelEd lists
 *
 * $Id$
 */

abstract class LabelEdAPI_ListAbstract extends LabelEdAPI_BaseAbstract
{
	/**
	 * Gets the total number of items avaliable for this listing
	 * 
	 * @return int
	 */
	public function getTotalItemCount()
	{
		return (int)((string)$this->getJson()->listing->totalItems);
	}
	
	/**
	 * Gets the total number pages avaliable for this listing
	 * 
	 * @return int
	 */
	public function getTotalPages()
	{
		return (int)((string)$this->getJson()->listing->totalPages);
	}
	
	/**
	 * Gets the currently loaded page number
	 * 
	 * @return int
	 */
	public function getCurrentPage()
	{
		return (int)((string)$this->getJson()->listing->currentPage);
	}
	
	/**
	 * Returns an array of ResourceAbstract from the listing
	 * 
	 * @return array
	 */
	abstract public function getItems();
}
