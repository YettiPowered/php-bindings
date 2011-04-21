<?php
require_once 'api/Item.inc.php';

$item = new LabelEdAPI_Item();
$item->webservice()->setBaseUri('http://whitelounge.tom.dev/1.0');

$item->setTypeId(6);

$item->setName('test');
$item->setPropertyValue('Artist', 'tom');
$item->save();



