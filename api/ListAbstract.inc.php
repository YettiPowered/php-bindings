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
	private
		$_items = array();
	
	/**
	 * Add an item to the list
	 * 
	 * @param mixed $item
	 * @return void
	 */
	protected function addItem($item)
	{
		$this->_items[] = $item;
	}
	
	/**
	 * Returns an array of items in this listing
	 * 
	 * @return array
	 */
	public function getItems()
	{
		if (empty($this->_items)) {
			$this->loadItemObjects();
		}
		
		return $this->_items;
	}
	
	/**
	 * Returns the total number of items avaliable for this listing
	 * 
	 * @return int
	 */
	public function getTotalItemCount()
	{
		return count($this->_items);
	}
	
	/**
	 * Load the item objects
	 * 
	 * @return void
	 */
	abstract protected function loadItemObjects();
	
	/**
	 * Returns the total number of pages available in this listing
	 * 
	 * @return int
	 */
	abstract public function getTotalPages();
	
	/**
	 * Returns the currently loaded page number
	 * 
	 * @return int
	 */
	abstract public function getCurrentPage();
}
