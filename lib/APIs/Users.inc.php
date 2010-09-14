<?php

/**
 * API for interfacing with LabelEd users over web services.
 *
 * $Id$
 *
 */
class LabelEdAPI_Users extends LabelEdAPI_Abstract
{
	private $_users;
	
	/**
	 * Returns an array of users or an individual user if requested by id
	 *
	 * @param mixed $userId
	 * @return array
	 */
	public function get($userId=false)
	{
		if ($userId) {
			return $this->getIndividualUser($userId);
		}
		else {
			return $this->getUserList();
		}
	}
	
	/**
	 * Returns a list of users
	 *
	 * @return array
	 */
	private function getUserList()
	{
		if (!$this->_users)
		{
			$this->webservice()->setRequestPath('/users.ws');
			$this->webservice()->setRequestMethod('get');
			$this->webservice()->makeRequest();
			
			$this->_users = $this->webservice()->getResponseXmlObject()->users->user;
		}
		
		return $this->_users;
	}
	
	/**
	 * Return a user as an array
	 *
	 * @param mixed $userId
	 * @return array
	 */
	private function getIndividualUser($userId)
	{
		$userArray = array();
		
		$this->webservice()->setRequestPath('/users/' . $userId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$user = $this->webservice()->getResponseXmlObject();
			
			if ($username = $user->xpath('user/username'))
			{
				$userElement = $user->xpath('user');
				
				$userArray = array('user' => array(
					'username'	=> (string)$username[0],
					'type' 		=> (string)$userElement[0]->attributes()->type,
					'email'		=> (string)$userElement[0]->email,
					'passHash'	=> (string)$userElement[0]->passHash,
				));
				
				foreach ($user->xpath('user/properties/property') as $property) {
					$userArray['properties'][(string)$property->attributes()->name] = (string)$property;
				}
			}
		}
		
		return $userArray;
	}
}
