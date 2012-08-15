<?php

namespace Yetti\API;

/**
 * Abstract base class for resource models.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

abstract class Resource_Abstract extends BaseAbstract
{
	/**
	 * Save this resource
	 *
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		if ($this->getId()) {
			return $this->update();
		}
		else
		{
			$return = $this->create();
			$this->getJson()->resource->resourceId = $this->webservice()->getResponseHeader('X-ResourceId');
			return $return;
		}
	}
	
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
	 * Set a property value
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function setPropertyValue($name, $value)
	{
		$this->getJson()->properties->{$name}->value = (string)$value;
	}
	
	/**
	 * Returns a property value
	 *
	 * @param string $name
	 * @return string
	 */
	public function getPropertyValue($name)
	{
		return (string)$this->getJson()->properties->{$name}->value;
	}
	
	/**
	 * Returns the revision comment for the current revision
	 * 
	 * @return string
	 */
	public function getRevisionComment()
	{
		return (string)$this->getJson()->resource->name;
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
	 * @return string $assetGroupName
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
	 * Load a resource template (for creating a new resource)
	 *
	 * @param int typeId
	 * @return bool
	 */
	abstract public function loadTemplate($typeId=null);
	
	/**
	 * Update an existing resource
	 *
	 * @return Result
	 */
	abstract protected function update();
	
	/**
	 * Create a new resource
	 *
	 * @return Result
	 */
	abstract protected function create();
}
