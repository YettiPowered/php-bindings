<?php

include_once('lib/LabelEdAPI.inc.php');
$api = new LabelEdAPI('http://label.sam.dev', 'sholman', '?$wE6HunVswe@eyu');

$item = $api->items->get('labeled-34');

echo '<pre>';
print_r($item);
echo '<pre>';

/*foreach ($api->items->get() as $item) {
	echo $item->identifier . '<br />';
}*/
