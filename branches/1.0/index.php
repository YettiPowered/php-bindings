<pre>
<?php
ob_start();
require_once 'api/Collections.inc.php';

$item = new LabelEdAPI_Collection();
$item->loadTemplate(6);
//$item->load(6726);
//$collections->load(6726);

$item->addAsset('Image', 6922, 'Alt Text', 'http://www.google.com');
//$item->addAsset('Category_image', 6922, 'Alt Text2', 'http://www.labelmedia.co.uk');

$item->setName('newcollection4');
$item->setPropertyValue('Name', 'Test Colleciton with asset2');
$item->setPropertyValue('Body', 'I am a WS set body');
$item->setPropertyValue('Summary', 'I am a WS set summary');
$item->save();

$output = ob_get_clean();
echo htmlspecialchars($output);

//$user->setPropertyValue('Additional_phone_no', '000000');
//$user->save();

//$collections->removeCollection(28);
//$collections->addCollection(28);
//print_r($collections->getCollectionIds());
//$collections->save();

/*foreach ($items->getItems() as $item) {
	echo $item->getId() . ' - ' . $item->getDisplayName() . ' (' . $item->getName() . ")\n";
}*/
