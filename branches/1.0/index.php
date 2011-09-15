<pre>
<?php
ob_start();
require_once 'api/Item.inc.php';

$item = new LabelEdAPI_Item();

$item->load(6726);
//$item->loadTemplate(6);
$output = ob_get_clean();

echo htmlspecialchars($item->getPropertyValue('Summary'));

echo htmlspecialchars($output);



