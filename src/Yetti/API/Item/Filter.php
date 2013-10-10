<?php

namespace Yetti\API;

/**
 * Item filter model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Item_Filter extends BaseAbstract
{
	private
	
		/**
		 * The filter type ID for this filter
		 * 
		 * @var int
		 */
		$_filterTypeId;
	
	/**
	 * Load a filter by ID
	 * 
	 * @param int $filterId
	 * @return void
	 */
	public function load($filterId)
	{
		$this->webservice()->setRequestPath('/filters/' . $this->getUriBase() . '/null/null/' . $filterId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			$this->_filterTypeId = $this->getJson()->filterTypeId;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Save this filter
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		$this->webservice()->setRequestPath('/filters/' . $this->getUriBase() . '/null/' . $this->_filterTypeId . '.ws');
		$this->webservice()->setRequestMethod('post');
		
		if ($this->getId())
		{
			$this->webservice()->setRequestMethod('put');
			$this->webservice()->setRequestParam('filterId', $this->getId());
		}
		
		$this->webservice()->setPostData(json_encode($this->getJson()));
		$result = $this->makeRequestReturnResult();
		
		if ($result->success() && !$this->getId()) {
			$this->getJson()->id = (int)$this->webservice()->getResponseHeader('X-ResourceId');
		}
		
		return $result;
	}
	
	/**
	 * Delete this filter
	 * 
	 * @return \Yetti\API\Result
	 */
	public function delete()
	{
		$this->webservice()->setRequestPath('/filters/' . $this->getUriBase() . '/null/null/' . $this->getId() . '.ws');
		$this->webservice()->setRequestMethod('delete');
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Set the filter type ID for this filter
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setFilterTypeId($id)
	{
		if (is_numeric($id)) {
			$this->_filterTypeId = (int)$id;
		}
	}
	
	/**
	 * Return the filter type ID
	 * 
	 * @return int
	 */
	public function getFilterTypeId()
	{
		return $this->_filterTypeId;
	}
	
	/**
	 * Returns the filter ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Set the name of this filter
	 * 
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->getJson()->name = $name;
	}
	
	/**
	 * Returns the name of this filter
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->getJson()->name;
	}
	
	/**
	 * Add a conditional filter by ID
	 * 
	 * @param int $filterId
	 * @return void
	 */
	public function addConditionalFilter($filterId)
	{
		if (is_numeric($filterId)) {
			$this->getJson()->conditionalFilters[] = (int)$filterId;
		}
	}
	
	/**
	 * Returns an array of conditional filter IDs
	 * 
	 * @return array
	 */
	public function getConditionalFilters()
	{
		return $this->getJson()->conditionalFilters;
	}
	
	/**
	 * Add an item to this filter
	 * 
	 * @param int $itemId
	 * @return void
	 */
	public function addItem($itemId)
	{
		if (is_numeric($itemId)) {
			$this->getJson()->items[] = (int)$itemId;
		}
	}
	
	/**
	 * Returns an array of items in this filter
	 * 
	 * @return array
	 */
	public function getItems()
	{
		return $this->getJson()->items;
	}
	
	/**
	 * Returns the json object
	 * 
	 * @return \stdClass
	 */
	public function getJson()
	{
		try {
			$return = parent::getJson();
		}
		catch (\yetti\API\Exception $e)
		{
			$template = new \stdClass();
			$template->id    = null;
			$template->name  = null;
			$template->items = array();
			$template->conditionalFilters = array();
			
			$this->setJson($template);
		}
		
		return parent::getJson();
	}
	
	/**
	 * Returns the URI base string
	 * 
	 * @return string
	 */
	protected function getUriBase()
	{
		return 'items';
	}
}
