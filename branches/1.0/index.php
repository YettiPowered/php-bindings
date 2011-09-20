<pre>
<?php
ob_start();
require_once 'api/ItemCollections.inc.php';

$collections = new LabelEdAPI_ItemCollections();
//$item->load(6726);
$collections->load(6726);

$output = ob_get_clean();
echo htmlspecialchars($output);

$collections->removeCollection(11414);
$collections->addCollection(28);
print_r($collections->getCollectionIds());
$collections->save();

/*foreach ($items->getItems() as $item) {
	echo $item->getId() . ' - ' . $item->getDisplayName() . ' (' . $item->getName() . ")\n";
}*/
