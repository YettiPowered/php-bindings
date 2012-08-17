<?php

namespace Yetti\API;

/**
 * Item filter model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Filter extends BaseAbstract
{
	private
		$_filterTypeId;
	
	/**
	 * Load a filter by ID
	 * 
	 * @param int $filterId
	 * @return void
	 */
	public function load($filterId)
	{
		$this->webservice()->setRequestPath('/items/filters/null/null/' . $filterId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
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
		$this->webservice()->setRequestPath('/items/filters/null/' . $this->_filterTypeId . '.ws');
		$this->webservice()->setRequestMethod('post');
		
		if ($this->getId())
		{
			$this->webservice()->setRequestMethod('put');
			$this->webservice()->setRequestParam('filterId', $this->getId());
		}
		
		$this->webservice()->setPostData(json_encode($this->getJson()));
		$result = $this->makeRequestReturnResult();
		
		if ($result->success()) {
			$this->getJson()->id = (int)$this->webservice()->getResponseHeader('X-ResourceId');
		}
		
		return $result;
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
			$template->id   = null;
			$template->name = null;
			$template->conditionalFilters = array();
			
			$this->setJson($template);
		}
		
		return parent::getJson();
	}
}
