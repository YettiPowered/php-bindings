<?php
require_once 'BaseAbstract.inc.php';

/**
 * API for interfacing with LabelEd items over web services.
 *
 * $Id$
 */

abstract class LabelEdAPI_ResourceAbstract extends LabelEdAPI_BaseAbstract
{
	private
		$_revisionComment = '';
		
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
		return (int)((string)$this->getXml()->item->resource->resourceId);
	}
	
	/**
	 * Sets the resource type ID
	 *
	 * @param int $id
	 * @return void
	 */
	public function setTypeId($id)
	{
		throw new Exception('Not yet implemented');
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
		throw new Exception('Not yet implemented');
	}
	
	/**
	 * Returns the resource name
	 *
	 * @return string
	 */
	public function getName()
	{
		return (int)((string)$this->getXml()->item->resource->name);
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
		throw new Exception('Not yet implemented');
	}
	
	/**
	 * Returns a property value
	 *
	 * @param string $name
	 * @return string
	 */
	public function getPropertyValue($name)
	{
		throw new Exception('Not yet implemented');
	}
	
	public function getRevisionComment()
	{
		return $this->_revisionComment;
	}
	
	public function setRevisionComment($comment)
	{
		$this->_revisionComment = (string)$comment;
	}
}
