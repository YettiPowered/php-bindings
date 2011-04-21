<?php

/**
 * API for interfacing with LabelEd items over web services.
 *
 * $Id$
 */

abstract class LabelEdAPI_ResourceAbstract extends LabelEdAPI_BaseAbstract
{
	private
		$_id,
		$_name,
		$_typeId,
		$_properties = array(),
		$_metaTitle,
		$_metaDescription,
		$_metaKeywords,
		$_revisionComment,
		
		$_shippingUnitValue,
		$_vatBandId;
	
	/**
	 * Load a resource template using getTypeId()
	 *
	 * @return bool
	 */
	abstract protected function loadTemplate();
	
	/**
	 * Update an existing resource
	 *
	 * @return LabelEdAPI_Result
	 */
	abstract protected function update();
	
	/**
	 * Create a new resource
	 *
	 * @return LabelEdAPI_Result
	 */
	abstract protected function create();
	
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
	 * Performs a webservice request and returns a result object
	 * Must be called after everything else has been set up
	 *
	 * @return LabelEdAPI_Result
	 */
	protected function makeRequestReturnResult()
	{
		$this->webservice()->makeRequest();
		$result = new LabelEdAPI_Result();
		
		if (substr($this->webservice()->getResponseCode(), 0, 1) != 2)
		{
			$response = $this->webservice()->getResponseXmlObject();
			
			if (isset($response->response) && isset($response->response->errors))
			{
				foreach ($response->response->errors->error as $error) {
					$result->addError($error);
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Returns the resource ID
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Sets the resource type ID
	 *
	 * @param int $id
	 * @return void
	 */
	public function setTypeId($id)
	{
		if (is_numeric($id))
		{
			$this->_typeId = (int)$id;
			
			if (!$this->getXml()) {
				$this->loadTemplate();
			}
		}
	}
	
	/**
	 * Returns the resource type ID
	 *
	 * @return int
	 */
	public function getTypeId()
	{
		return $this->_typeId;
	}
	
	/**
	 * Set the resource name (identifier)
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		if (is_string($name)) {
			$this->_name = $name;
		}
	}
	
	/**
	 * Returns the resource name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
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
		if (is_string($name)) {
			$this->_properties[$name] = $value;
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
		$name = (string)$name;
		return isset($this->_properties[$name]) ? $this->_properties[$name] : false;
	}
	
	public function getMetaTitle()
	{
		return $this->_metaTitle;
	}
	
	public function getMetaDescription()
	{
		return $this->_metaDescription;
	}
	
	public function getMetaKeywords()
	{
		return $this->_metaKeywords;
	}
	
	public function getShippingUnitValue()
	{
		return $this->_shippingUnitValue;
	}
	
	public function getVatBandId()
	{
		return $this->_vatBandId;
	}
	
	public function getRevisionComment()
	{
		return $this->_revisionComment;
	}
}
