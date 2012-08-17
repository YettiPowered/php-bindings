<?php

namespace Yetti\API;

/**
 * Item filter type model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Filter_Type extends BaseAbstract
{
	private
		$_itemTypeId;
	
	/**
	 * Load a filter type by ID
	 * 
	 * @param int $filterTypeId
	 * @return void
	 */
	public function load($filterTypeId)
	{
		$this->webservice()->setRequestPath('/items/filters/null/' . $filterTypeId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Save this filter type
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		$this->webservice()->setRequestPath('/items/filters/' . $this->_itemTypeId . '.ws');
		$this->webservice()->setRequestMethod('post');
		
		if ($this->getId())
		{
			$this->webservice()->setRequestMethod('put');
			$this->webservice()->setRequestParam('filterTypeId', $this->getId());
		}
		
		$this->webservice()->setPostData(json_encode($this->getJson()));
		$result = $this->makeRequestReturnResult();
		
		if ($result->success()) {
			$this->getJson()->id = (int)$this->webservice()->getResponseHeader('X-ResourceId');
		}
		
		return $result;
	}
	
	/**
	 * Set the item type ID for this filter
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setItemTypeId($id)
	{
		if (is_numeric($id)) {
			$this->_itemTypeId = (int)$id;
		}
	}
	
	/**
	 * Returns the filter type ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Set the name of this filter type
	 * 
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->getJson()->name = $name;
	}
	
	/**
	 * Returns the name of this filter type
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
			
			$this->setJson($template);
		}
		
		return parent::getJson();
	}
}
