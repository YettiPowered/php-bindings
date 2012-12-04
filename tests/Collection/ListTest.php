<?php

namespace Yetti\API\Tests;
use Yetti\API\Collection_List;

/**
 * Test methods for the collection list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Collection_ListTest extends AuthAbstract
{
	public function testParentId()
	{
		$typeId = 4;
		
		$collectionList = new Collection_List();
		$collectionList->load($typeId);
		
		$this->assertNotEmpty($collectionList->getItems());
		
		foreach ($collectionList->getItems() as $collection) {
			$this->assertInternalType('int', $collection->getParentId());
		}
	}
}
