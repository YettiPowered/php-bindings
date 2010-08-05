<?php

echo '<pre>';

include_once('lib/LabelEdAPI.inc.php');
$api = new LabelEdAPI('http://asd.sam.dev', 'sholman', '?$wE6HunVswe@eyu');

print_r($api->items->get('another-post'));

/*$newItem = 'test-new-item';

$created = $api->items->create(1, array(
	'item' => array(
		'identifier' => $newItem,
	),
	'properties' => array(
		'Name' => 'modified',
	)
));

if ($created) {
	print_r($api->items->get($newItem));
}*/

/*foreach ($api->items->get() as $item) {
	echo $item->identifier . '<br />';
}*/

echo '</pre>';