<?php

require_once 'BaseAbstract.inc.php';
require_once 'Property.inc.php';

/**
 * API for interfacing with Yetti resources over web services.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

abstract class LabelEdAPI_ResourceAbstract extends LabelEdAPI_BaseAbstract
{
	/**
	 * Load a resource template using 
	 *
	 * @param int typeId
	 * @return bool
	 */
	abstract public function loadTemplate($typeId=null);
	
	/**
	 * Update an existing resource
	 *
	 * @return LabelEdAPI_Result
	 */
	abstract public function update();
	
	/**
	 * Create a new resource
	 *
	 * @return LabelEdAPI_Result
	 */
	abstract public function create();
	
	/**
	 * Save this resource
	 *
	 * @return LabelEdAPI_Result
	 */
	public function save()
	{
		if ($this->getId()) {
			return $this->update();
		}
		else
		{
			$return = $this->create();
			$this->getJson()->item->resource->resourceId = $this->webservice()->getResponseHeader('X-ResourceId');
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
		return (int)((string)$this->getJson()->item->resource->resourceId);
	}
		
	/**
	 * Returns the resource type ID
	 *
	 * @return int
	 */
	public function getTypeId()
	{
		return (int)((string)$this->getJson()->item->resource->resourceTypeId);
	}
	
	/**
	 * Set the resource name (identifier)
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->getJson()->item->resource->identifier = (string)$name;
	}
	
	/**
	 * Returns the resource name
	 *
	 * @return string
	 */
	public function getName()
	{
		return (string)$this->getJson()->item->resource->identifier;
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
		$this->getJson()->item->properties->{$name}->value = (string)$value;
	}
	
	/**
	 * Returns a property value
	 *
	 * @param string $name
	 * @return string
	 */
	public function getPropertyValue($name)
	{
		return (string)$this->getJson()->item->properties->{$name}->value;
	}
	
	/**
	 * Returns the revision comment for the current revision
	 * 
	 * @return string
	 */
	public function getRevisionComment()
	{
		return (string)$this->getJson()->item->resource->name;
	}
	
	/**
	 * Sets the revision comment for the current revision
	 * 
	 * @param string $comment
	 * @return void
	 */
	public function setRevisionComment($comment)
	{
		$this->getJson()->item->revision->comment = (string)$comment;
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
		$asset = $this->getJson()->item->assets->addChild($assetGroupName);
		
		if (isset($altText)) {
			$asset->addChild('altText', $altText);
		}
		
		if (isset($url)) {
			$asset->addChild('url', $url);
		}
		
		$item = $asset->addChild('item');
		$item->addChild('resourceId', $resourceId);
	}
	
	/**
	 * Returns an array of property elements
	 * 
	 * @return array
	 */
	public function getProperties()
	{
		$properties = array();
		
		foreach ($this->getJson()->item->properties as $name => $property) {
			$properties[$name] = new LabelEdAPI_Property($property);
		}
		
		return $properties;
	}
}
