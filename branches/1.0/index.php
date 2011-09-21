<pre>
<?php
ob_start();
require_once 'api/Collections.inc.php';

$collection = new LabelEdAPI_Collection();
$collection->load(11422);
//$item->load(6726);
//$collections->load(6726);

$collection->setPropertyValue('Body', 'I am a WS set body');
$collection->save();

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
