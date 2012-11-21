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
	
		/**
		 * Holds the list items
		 * 
		 * @var \Yetti\API\ListIterator
		 */
		$_items = array(),
		
		/**
		 * Whether or not getItems() should auto paginate
		 * 
		 * @var bool
		 */
		$_autoPagination = false;
	
	/**
	 * Add an item to the list
	 * 
	 * @param mixed $item
	 * @return void
	 */
	protected function addItem($item)
	{
		if (!$this->_items)
		{
			$this->_items = new \Yetti\API\ListIterator();
			$this->_items->setListModel($this);
		}
		
		$this->_items->addItem($item);
		
		if ($this->shouldAutoPaginate()) {
			$this->_items->setTotalItemCount($this->getTotalItemCount());
		}
	}
	
	/**
	 * Returns a list iterator object containing items in this listing
	 * 
	 * @return \Yetti\API\ListIterator
	 */
	public function getItems()
	{
		if (empty($this->_items)) {
			$this->loadItemObjects();
		}
		
		return $this->_items;
	}
	
	/**
	 * Returns the total number of items available for this listing
	 * 
	 * @return int
	 */
	public function getTotalItemCount()
	{
		return count($this->getItems());
	}
	
	/**
	 * Set whether or not getItems() should auto paginate
	 * 
	 * @param bool $autoPaginate
	 * @return void
	 */
	protected function setAutoPaginate($autoPaginate=true)
	{
		$this->_autoPagination = (bool)$autoPaginate;
	}
	
	/**
	 * Returns whether or not getItems() should auto paginate
	 * 
	 * @return bool
	 */
	public function shouldAutoPaginate()
	{
		return (bool)$this->_autoPagination;
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
