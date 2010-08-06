<?php

include_once('lib/LabelEdAPI.inc.php');
$api = new LabelEdAPI('http://asd.sam.dev', 'sholman', '?$wE6HunVswe@eyu');

/*$item = $api->items->get('another-post');

echo '<pre>';
print_r($item);
echo '</pre>';*/

$template = $api->items->template(1);
$identifier = $template['item']['identifier'] = ('test-moo' . time());

$template['properties']['Name'] = 'Test Moo';
$template['properties']['Date'] = time();
$template['properties']['Body'] = 'Monkeys';

$result = $api->items->create($template);

if ($result->success())
{
	echo '<pre>';
	print_r($api->items->get($identifier));
	echo '</pre>';
}
else
{
	foreach ($result->getErrors() as $error) {
		echo $error . '<br />';
	}
}
