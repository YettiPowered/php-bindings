<?php

require_once 'api/Webservice.inc.php';

$accessKey   = isset($_POST['accessKey']) ? $_POST['accessKey'] : null;
$privateKey  = isset($_POST['privateKey']) ? $_POST['privateKey'] : null;
$baseUri 	 = isset($_POST['baseUri']) ? $_POST['baseUri'] : null;
$requestPath = isset($_POST['requestPath']) ? $_POST['requestPath'] : null;
$pathEnd 	 = strpos($requestPath, '?');
parse_str(parse_url($requestPath, PHP_URL_QUERY), $requestParams);

$webservice = new LabelEdAPI_WebService();
$webservice->setAccessKey($accessKey);
$webservice->setPrivateKey($privateKey);
$webservice->setBaseUri($baseUri);
$webservice->setRequestPath($pathEnd ? substr($requestPath, 0, $pathEnd) : $requestPath);

foreach ($requestParams as $name => $value) {
	$webservice->setRequestParam($name, $value);
}

try
{
	$webservice->makeRequest();
	
	$response  = $webservice->getRequestMethod() . ' ' . str_replace($baseUri, '', $webservice->getRequestUri()) . " HTTP/1.1\n";
	$response .= 'Host: ' . $baseUri .  "\n";
	$response .= 'X-Authorization: ' . $accessKey . ':' . $webservice->getRequestSignature();
	$response .= "\n\n";
	$response .= $webservice->getRawResponse();
}
catch (Exception $e) {
	$response = $e->getMessage();
}

?>

<html>
	<head>
		<title>Yetti webservice proxy</title>
		
		<style type="text/css">
			form fieldset ul {
				list-style: none;
				margin: 0;
				padding: 0;
			}
			form fieldset ul li {
				margin-bottom: 10px;
			}
			form fieldset label {
				display: block;
				margin-bottom: 3px;
			}
			input[type="text"] {
				width: 600px;
			}
			textarea {
				width: 1000px;
				height: 600px;
				font-family: courier;
				font-size: 12px;
			}
		</style>
	</head>
	<body>
		<form method="post" action="">
			<fieldset>
				<ul>
					<li>
						<label>Access key (username): </label>
						<input type="text" name="accessKey" value="<?php echo $accessKey; ?>" />
					</li>
					
					<li>
						<label>Private key: </label>
						<input type="text" name="privateKey" value="<?php echo $privateKey; ?>" />
					</li>
					
					<li>
						<label>Base URI: </label>
						<input type="text" name="baseUri" value="<?php echo $baseUri; ?>" />
					</li>
					
					<li>
						<label>Request path: </label>
						<input type="text" name="requestPath" value="<?php echo $requestPath; ?>" />
					</li>
					
					<li>
						<label>Response (leave blank): </label>
						<textarea name="response"><?php echo htmlentities($response); ?></textarea>
					</li>
				</ul>
				
				<button type="submit">Make request</button>
			</fieldset>
		</form>
	</body>
</html>