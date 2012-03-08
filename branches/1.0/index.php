<?php

require_once 'api/Items.inc.php';

LabelEdAPI_WebService::setDefaultBaseUri('http://yetti_demo.sam.dev');
LabelEdAPI_WebService::setDefaultAccessKey('test');
LabelEdAPI_WebService::setDefaultPrivateKey('gidsGe64dd34z!3445£$Ds');

$item = new LabelEdAPI_Item();

if ($item->load(67)) {
	echo $item->getName();
}
else {
	echo 'Failed to load item.';
}

//$item->loadTemplate(6);
//$collections->load(6726);
//$item->addPricingTier(30);
//$item->save();
//$item->addAsset('Image', 6922, 'Alt Text', 'http://www.google.com');
//$item->addAsset('Category_image', 6922, 'Alt Text2', 'http://www.labelmedia.co.uk');
/*
$item->addTier();

$item->setName('newitemprice1');
$item->setPropertyValue('Name', 'Test Item with asset and price');
$item->setPropertyValue('Body', 'I am a WS set body');
$item->setPropertyValue('Summary', 'I am a WS set summary');
$item->save();
*/

//$user->setPropertyValue('Additional_phone_no', '000000');
//$user->save();

//$collections->removeCollection(28);
//$collections->addCollection(28);
//print_r($collections->getCollectionIds());
//$collections->save();

/*foreach ($items->getItems() as $item) {
	echo $item->getId() . ' - ' . $item->getDisplayName() . ' (' . $item->getName() . ")\n";
}*/
