<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the language list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Language_ListTest extends \PHPUnit_Framework_TestCase
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
	
	public function testLanguageCount()
	{
		$languages = new \Yetti\API\Language_List();
		$this->assertTrue($languages->load());
		
		$this->assertCount(2, $languages->getItems());
	}
	
	public function testWhichLanguagesAreAvailable()
	{
		$languages = new \Yetti\API\Language_List();
		$this->assertTrue($languages->load());
		
		$languageArray = $languages->getItems();
		
		$this->assertEquals('GB', $languageArray[0]->getCountryCode());
		$this->assertEquals('FR', $languageArray[1]->getCountryCode());
	}
}
