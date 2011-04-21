<?php

/**
 * API for interfacing with LabelEd collections over web services.
 *
 * $Id$
 */
class LabelEdAPI_Collections extends LabelEdAPI_Abstract
{
	private
		$_collections;
	
	/**
	 * Returns an array of collections or an individual collection if requested by id
	 *
	 * @param mixed $collectionId
	 * @return array
	 */
	public function get($collectionId=false)
	{
		if ($collectionId) {
			return $this->getIndividualCollection($collectionId);
		}
		else {
			return $this->getCollectionList();
		}
	}
	
	public function getCollectionList()
	{
		if (!$this->_collections)
		{
			$this->webservice()->setRequestPath('/collections.ws');
			$this->webservice()->setRequestMethod('get');
			$this->webservice()->makeRequest();
			
			$collections = array();
			
			foreach ($this->webservice()->getResponseXmlObject()->collections->collection as $collection)
			{
				$collections[] = array(
					'id'			=> (int)$collection->attributes()->id,
					'typeId' 		=> (int)$collection->attributes()->typeId,
					'identifier'	=> (string)$collection->identifier,
					'parentId'		=> (string)$collection->parentId,
				);
			}
			
			$this->_collections = $collections;
		}
		
		return $this->_collections;
	}
}
