<pre>
<?php
ob_start();
require_once 'api/Item.inc.php';

$item = new LabelEdAPI_Item();

$item->load(6726);
$output = ob_get_clean();

echo htmlspecialchars($output);



