<pre>
<?php
ob_start();
require_once 'api/Users.inc.php';

$user = new LabelEdAPI_User();
//$item->load(6726);
$user->load(2);

$output = ob_get_clean();
echo htmlspecialchars($output);

/*foreach ($items->getItems() as $item) {
	echo $item->getId() . ' - ' . $item->getDisplayName() . ' (' . $item->getName() . ")\n";
}*/
