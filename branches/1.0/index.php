<pre>
<?php
ob_start();
require_once 'api/Items.inc.php';

$items = new LabelEdAPI_Items();
//$item->load(6726);
$items->load(6, 5);

$output = ob_get_clean();
echo htmlspecialchars($output);

foreach ($items->getItems() as $item) {
	echo $item->getId() . ' - ' . $item->getDisplayName() . ' (' . $item->getName() . ")\n";
}


