<pre>
<?php
ob_start();
require_once 'api/Item.inc.php';

$item = new LabelEdAPI_Item();
//$item->load(6726);
$item->loadTemplate(6);


$item->setName('test-create');
$item->setPropertyValue('Summary', "<h1>New Data</h1>");
$item->setPropertyValue('Name', "New name");
echo "\n\n\n\n";
$item->save();

$output = ob_get_clean();
echo htmlspecialchars($output);



