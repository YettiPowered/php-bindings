<?php

include_once('lib/LabelEdAPI.inc.php');
$api = new LabelEdAPI('http://asd.sam.dev', 'sholman', '?$wE6HunVswe@eyu');

$item = $api->items->get('another-post');

echo '<pre>';
print_r($item);
echo '</pre>';

$identifier = $item['item']['identifier'] = ('test-moo' . time());

if ($api->items->create($item))
{
	echo '<pre>';
	print_r($api->items->get($identifier));
	echo '</pre>';
}
