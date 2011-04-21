<?php
require_once 'api/Item.inc.php';

$item = new LabelEdAPI_Item();
$item->webservice()->setBaseUri('http://whitelounge.tom.dev/1.0');

$item->setTypeId(6);

$item->setName('test345345');
$item->setPropertyValue('Name', 'testItem-oidjfpaodn');
$item->setPropertyValue('Summary', 't34fgergqerg');
$item->setPropertyValue('Artist', 'tom');
$item->save();



