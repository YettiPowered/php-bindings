<?php

namespace Yetti\API;

/**
 * List iterator object
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class ListIterator implements \Iterator, \Countable, \ArrayAccess
{
	private
		
		/**
		 * The array of items.
		 * 
		 * @var array
		 */
		$_items = array(),
		
		/**
		 * If set, this value will be returned for count, instead of the actual count($this->_items).
		 * 
		 * @var int
		 */
		$_totalItemCount = 0,
		
		/**
		 * Holds an instance of the list model that owns this iterator.
		 * This model will be used to paginate should we need more items.
		 * 
		 * @var \Yetti\API\ListAbstract
		 */
		$_listModel;
		
	/**
	 * Add an item to the iterator list
	 * 
	 * @param mixed $item
	 * @return void
	 */
	public function addItem($item)
	{
		$this->_items[] = $item;
	}
	
	/**
	 * Set the total item count
	 * 
	 * @param int $count
	 * @return void
	 */
	public function setTotalItemCount($count)
	{
		if (is_numeric($count)) {
			$this->_totalItemCount = (int)$count;
		}
	}
	
	/**
	 * Set the list model that owns this iterator
	 * 
	 * @param \Yetti\API\ListAbstract $model
	 * @return void
	 */
	public function setListModel(\Yetti\API\ListAbstract $model)
	{
		$this->_listModel = $model;
	}
	
	/**
	 * Returns the first item in the list
	 * 
	 * @return mixed
	 */
	public function first()
	{
		if ($this->count()) {
			return $this->_items[0];
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator::current()
	 */
	public function current()
	{
		return current($this->_items);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator::next()
	 */
	public function next()
	{
		$key  = $this->key();
		$next = next($this->_items);
		
		if ($next === false && ($key+1) < $this->count())
		{
			foreach ($this->_items as &$item) {
				$item = null;
			}
			
			$this->paginate();
			$next = current($this->_items);
		}
		
		return $next;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator::key()
	 */
	public function key()
	{
		return key($this->_items);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator::valid()
	 */
	public function valid()
	{
		return $this->current() !== false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator::rewind()
	 */
	public function rewind()
	{
		reset($this->_items);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Countable::count()
	 */
	public function count()
	{
		$count = count($this->_items);
		return $this->_totalItemCount > $count ? $this->_totalItemCount : $count;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetExists()
	 */
	public function offsetExists($offset)
	{
		return isset($this->_items[$offset]);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetGet($offset)
	{
		return $this->offsetExists($offset) ? $this->_items[$offset] : null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetSet()
	 */
	public function offsetSet($offset, $value)
	{
		$this->_items[$offset] = $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetUnset()
	 */
	public function offsetUnset($offset)
	{
		unset($this->_items[$offset]);
	}
	
	/**
	 * Load the next page of items
	 * 
	 * @return void
	 */
	private function paginate()
	{
		if ($this->_listModel) {
			$this->_listModel->loadNextPage();
		}
	}
}
