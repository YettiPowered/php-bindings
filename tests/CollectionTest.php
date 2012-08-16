<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the collection model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class CollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Set up access credentials for these tests
	 * 
	 * @return void
	 */
	protected function setUp()
	{
		\Yetti\API\Webservice::setDefaultBaseUri(YETTI_API_BASE_URI);
		\Yetti\API\Webservice::setDefaultAccessKey(YETTI_API_ACCESS_KEY);
		\Yetti\API\Webservice::setDefaultPrivateKey(YETTI_API_PRIVATE_KEY);
	}
	
	public function testSaveValidCollection()
	{
		$collection = new \Yetti\API\Collection();
		$this->assertTrue($collection->loadTemplate(4));
		$this->assertFalse($collection->save()->success());
		
		$collection->setName('A test collection ' . microtime(true));
		$this->assertFalse($collection->save()->success());
		
		$collection->setPropertyValue('Name', 'Test collection');
		$this->assertTrue($collection->save()->success());
		
		return $collection;
	}
	
	/**
	 * @depends testSaveValidCollection
	 */
	public function testLoadCollection(\Yetti\API\Collection $inCollection)
	{
		$collection = new \Yetti\API\Collection();
		$this->assertFalse($collection->load(-1));
		$this->assertTrue($collection->load($inCollection->getId()));
		
		$this->assertEquals('a-test-collection', substr($collection->getName(), 0, 17));
		$this->assertEquals('Test collection', $collection->getPropertyValue('Name'));
		
		return $collection;
	}
	
	/**
	 * @depends testLoadCollection
	 */
	public function testAddChildCollection(\Yetti\API\Collection $inCollection)
	{
		$collection = new \Yetti\API\Collection();
		$this->assertTrue($collection->loadTemplate(4));
		
		$collection->setParentId($inCollection->getId());
		$collection->setName('A test child collection');
		$collection->setPropertyValue('Name', 'Test collection');
		$this->assertTrue($collection->save()->success());
		$childCollectionId = $collection->getId();
		
		$collection = new \Yetti\API\Collection();
		$this->assertTrue($collection->load($childCollectionId));
		
		$this->assertEquals($inCollection->getId(), $collection->getParentId());
		$this->assertEquals('a-test-child-collection', $collection->getName());
	}
}
