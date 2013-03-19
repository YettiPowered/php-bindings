<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the collection filter type list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Collection_Filter_Type_ListTest extends AuthAbstract
{
	public function testInvalidTypeFailsToLoad()
	{
		$list = new \Yetti\API\Collection_Filter_Type_List();
		$this->assertFalse($list->load(-1));
	}
	
	public function testValidTypeLoad()
	{
		$collection = new \Yetti\API\Collection();
		$collection->loadTemplate(4);
		$collection->setIdentifier('A collection with filters ' . microtime(true));
		$collection->setPropertyValue('Name', 'A collection with filters');
		$this->assertTrue($collection->save()->success());
		
		$list = new \Yetti\API\Collection_Filter_Type_List();
		$this->assertTrue($list->load($collection->getId()));
	}
}
