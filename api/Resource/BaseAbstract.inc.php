<?php

namespace Yetti\API;

/**
 * Abstract base class for resource models.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

abstract class Resource_BaseAbstract extends BaseAbstract
{
	private
		$_countryCode;
	
	/**
	 * Loads a resource by ID or identifier
	 *
	 * @param mixed $resourceId int ID or string identifier
	 * @param string $countryCode
	 * @return bool
	 */
	public function load($resourceId, $countryCode=null)
	{
		if ($countryCode && is_string($countryCode)) {
			$this->_countryCode = '/' . strtolower($countryCode);
		}
		
		$requestPath = $this->_countryCode . '/' . $this->getPluralName() . '/null/' . $resourceId . '.ws';
		
		$this->webservice()->setRequestPath($requestPath);
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject()->item);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Load a resource template for the given type ID
	 * 
	 * @param int $typeId
	 * @return bool
	 */
	public function loadTemplate($typeId=null)
	{
		$this->webservice()->setRequestPath('/templates/' . $this->getSingularName() . '/' . ((int)$typeId) . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject()->item);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Save this resource
	 *
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		if (!$this->getId()) {
			$result = $this->create();
		}
		else {
			$result = $this->update();
		}
		
		if ($result->success()) {
			$this->_afterSave();
		}
		
		return $result;
	}
	
	/**
	 * Create a new resource
	 * 
	 * @return \Yetti\API\Result
	 */
	protected function create()
	{
		$this->webservice()->setRequestPath('/' . $this->getPluralName() . '.ws');
		$this->webservice()->setRequestMethod('post');
		
		$this->webservice()->setPostData(json_encode(array('item' => $this->getJson())));
		$result = $this->makeRequestReturnResult();
		
		if ($result->success()) {
			$this->getJson()->resource->resourceId = $this->webservice()->getResponseHeader('X-ResourceId');
		}
		
		return $result;
	}
	
	/**
	 * Update an existing resource
	 * 
	 * @return \Yetti\API\Result
	 */
	protected function update()
	{
		$this->webservice()->setRequestPath($this->_countryCode . '/' . $this->getPluralName() . '.ws');
		$this->webservice()->setRequestParam('typeId', $this->getTypeId());
		$this->webservice()->setRequestParam('resourceId', $this->getId());
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData(json_encode(array('item' => $this->getJson())));
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * This method can be overridden by derived classes to perform actions after a successful save
	 * 
	 * @return void
	 */
	protected function _afterSave() {}
	
	/**
	 * Returns the resource ID
	 *
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->resource->resourceId;
	}
	
	/**
	 * Returns the language ID
	 * 
	 * @return int
	 */
	public function getLanguageId()
	{
		return (int)$this->getJson()->resource->languageId;
	}
	
	/**
	 * Returns the revision ID
	 * 
	 * @return int
	 */
	public function getRevisionId()
	{
		return (int)$this->getJson()->revision->revisionId;
	}
	
	/**
	 * Returns the resource type ID
	 *
	 * @return int
	 */
	public function getTypeId()
	{
		return (int)$this->getJson()->resource->resourceTypeId;
	}
	
	/**
	 * Set whether or not this resource is available in the current language
	 * 
	 * @param bool $active
	 * @return void
	 */
	public function setLanguageActive($active=true)
	{
		$this->getJson()->resource->languageActive = (bool)$active;
	}
	
	/**
	 * Returns whether or not this resource is active in the current language
	 * 
	 * @return bool
	 */
	public function isLanguageActive()
	{
		return (bool)$this->getJson()->resource->languageActive;
	}
	
	/**
	 * Set the resource name (identifier)
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->getJson()->resource->identifier = (string)$name;
	}
	
	/**
	 * Returns the resource name
	 *
	 * @return string
	 */
	public function getName()
	{
		return (string)$this->getJson()->resource->identifier;
	}
	
	/**
	 * Returns the resource's display name
	 *
	 * @return string
	 */
	public function getDisplayName()
	{
		return (string)$this->getJson()->item->resource->name;
	}
	
	/**
	 * Set a property value
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function setPropertyValue($name, $value)
	{
		if (isset($this->getJson()->properties->{$name})) {
			$this->getJson()->properties->{$name}->value = (string)$value;
		}
	}
	
	/**
	 * Returns a property value
	 *
	 * @param string $name
	 * @return string
	 */
	public function getPropertyValue($name)
	{
		if (isset($this->getJson()->properties->{$name})) {
			return (string)$this->getJson()->properties->{$name}->value;
		}
	}
	
	/**
	 * Returns the revision comment for the current revision
	 * 
	 * @return string
	 */
	public function getRevisionComment()
	{
		return (string)$this->getJson()->revision->comment;
	}
	
	/**
	 * Sets the revision comment for the current revision
	 * 
	 * @param string $comment
	 * @return void
	 */
	public function setRevisionComment($comment)
	{
		$this->getJson()->revision->comment = (string)$comment;
	}
	
	/**
	 * Clear the current attached assets
	 * 
	 * @param string $assetGroupName
	 * @return void
	 */
	public function clearAttachedAssets($assetGroupName=null)
	{
		$this->getJson()->assets = (array)$this->getJson()->assets;
		
		if ($assetGroupName) {
			$this->getJson()->assets[$assetGroupName] = array();
		}
		else {
			$this->getJson()->assets = array();
		}
	}
	
	/**
	 * Adds a new asset to the resource
	 * 
	 * @param string $assetGroupName
	 * @param int $resourceId
	 * @param string $altText
	 * @param string $url
	 */
	public function addAsset($assetGroupName, $resourceId, $altText=null, $url=null)
	{
		$this->getJson()->assets[$assetGroupName][] = array
		(
			'item'    => array(
				'resourceId' => $resourceId,
			),
			'altText' => $altText,
			'url' 	  => $url,
		);
	}
	
	/**
	 * Returns an array of property elements
	 * 
	 * @return array
	 */
	public function getProperties()
	{
		$properties = array();
		
		foreach ($this->getJson()->properties as $name => $property)
		{
			$properties[$name] = new \Yetti\API\Resource_Property();
			$properties[$name]->setJson($property);
		}
		
		return $properties;
	}
	
	/**
	 * Returns an array of attached asset group elements
	 * 
	 * @return array
	 */
	public function getAttachedAssets()
	{
		$assets = array();
		
		foreach ($this->getJson()->assets as $name => $group)
		{
			$assets[$name] = new \Yetti\API\Resource_Asset_Group();
			$assets[$name]->setJson($group);
		}
		
		return $assets;
	}
	
	/**
	 * Returns a plural name for resources of this type
	 * 
	 * @return string
	 */
	private function getPluralName()
	{
		return $this->getSingularName() . 's';
	}
	
	/**
	 * Return a singular name for this resource type
	 * 
	 * @return string
	 */
	abstract protected function getSingularName();
}
