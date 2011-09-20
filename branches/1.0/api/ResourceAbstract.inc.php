<?php
require_once 'BaseAbstract.inc.php';

/**
 * API for interfacing with LabelEd items over web services.
 *
 * $Id$
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
		else {
			return $this->create();
		}
	}
	
	/**
	 * Returns the resource ID
	 *
	 * @return int
	 */
	public function getId()
	{
		return (int)((string)$this->getXml()->item->resource->resourceId);
	}
		
	/**
	 * Returns the resource type ID
	 *
	 * @return int
	 */
	public function getTypeId()
	{
		return (int)((string)$this->getXml()->item->resource->resourceTypeId);
	}
	
	/**
	 * Set the resource name (identifier)
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->getXml()->item->resource->identifier = (string)$name;
	}
	
	/**
	 * Returns the resource name
	 *
	 * @return string
	 */
	public function getName()
	{
		return (string)$this->getXml()->item->resource->identifier;
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
		$this->getXml()->item->properties->{$name}->value = (string)$value;
	}
	
	/**
	 * Returns a property value
	 *
	 * @param string $name
	 * @return string
	 */
	public function getPropertyValue($name)
	{
		return (string)$this->getXml()->item->properties->{$name}->value;
	}
	
	/**
	 * Gets the revision comment for the current revision
	 * 
	 * @return string
	 */
	public function getRevisionComment()
	{
		return (string)$this->getXml()->item->resource->name;
	}
	
	/**
	 * Sets the revision comment for the current revision
	 * 
	 * @param string $comment
	 * @return void
	 */
	public function setRevisionComment($comment)
	{
		$this->getXml()->item->resource->name = (string)$value;
	}
}
