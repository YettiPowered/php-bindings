<?php

/**
 * API for interfacing with LabelEd groups over web services.
 *
 * $Id$
 *
 */
class LabelEdAPI_Groups extends LabelEdAPI_Abstract
{
	private $_groups;
	
	/**
	 * Returns an array of groups or an individual group if requested by id
	 *
	 * @param mixed $groupId
	 * @return array
	 */
	public function get($groupId=false)
	{
		if ($groupId) {
			return $this->getIndividualGroup($groupId);
		}
		else {
			return $this->getGroupList();
		}
	}
	
	/**
	 * Returns a list of groups
	 *
	 * @return array
	 */
	private function getGroupList()
	{
		if (!$this->_groups)
		{
			$this->webservice()->setRequestPath('/groups.ws');
			$this->webservice()->setRequestMethod('get');
			$this->webservice()->makeRequest();
			
			$groups = array();
			
			foreach ($this->webservice()->getResponseXmlObject()->groups->group as $group)
			{
				$groups[] = array(
					'id'			=> (int)$group->attributes()->id,
					'typeId' 		=> (int)$group->attributes()->typeId,
					'authorised' 	=> (int)$group->attributes()->authorised,
				);
			}
			
			$this->_groups = $groups;
		}
		
		return $this->_groups;
	}
	
	/**
	 * Return a group as an array
	 *
	 * @param mixed $groupId
	 * @return array
	 */
	private function getIndividualGroup($groupId)
	{
		$groupArray = array();
		
		$this->webservice()->setRequestPath('/groups/' . $groupId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$group = $this->webservice()->getResponseXmlObject();
			
			$groupElement = $group->xpath('group');
			
			if (!empty($groupElement[0]))
			{
				$groupArray = array('group' => array(
					'type' 		=> (string)$groupElement[0]->attributes()->type,
				));
				
				foreach ($group->xpath('group/properties/property') as $property)
				{
					$groupArray['properties'][(string)$property->attributes()->name] = array(
						'value'		=> (string)$property,
						'dataType'	=> (string)$property->attributes()->dataType,
						'required'	=> (string)$property->attributes()->required,
					);
				}
				
				$groupArray['attachedItems'] = array();
				
				foreach ($group->xpath('group/attachedItems/itemGroup') as $itemGroup)
				{
					$groupArray['attachedItems'][(string)$itemGroup->attributes()->id]['itemGroupId'] = (string)$itemGroup->attributes()->id;
					
					foreach ($itemGroup->xpath('item') as $item)
					{
						$groupArray['attachedItems'][(string)$itemGroup->attributes()->id]['items'][] = array(
							'itemId'	=> (string)$item->attributes()->id,
							'caption' 	=> (string)$item->caption,
							'url'		=> (string)$item->url,
						);
					}
				}
				
				$groupArray['attachedItems'] = array_values($groupArray['attachedItems']);
			}
		}
		
		return $groupArray;
	}
}
