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

class Language_ListTest extends AuthAbstract
{
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
		
		$this->assertEquals(1, $languageArray[0]->getId());
		$this->assertEquals('GB', $languageArray[0]->getCountryCode());
		$this->assertEquals(true, $languageArray[0]->isDefault());
		
		$this->assertEquals(2, $languageArray[1]->getId());
		$this->assertEquals('FR', $languageArray[1]->getCountryCode());
		$this->assertEquals(false, $languageArray[1]->isDefault());
	}
}
