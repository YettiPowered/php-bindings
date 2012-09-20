<?php

require_once '../API/Autoloader.php';
Yetti\API\Autoloader::register();

$accessKey   	= isset($_POST['accessKey']) ? $_POST['accessKey'] : null;
$privateKey  	= isset($_POST['privateKey']) ? $_POST['privateKey'] : null;
$method 	 	= isset($_POST['method']) ? $_POST['method'] : null;
$postData 	 	= isset($_POST['postData']) ? $_POST['postData'] : null;
$baseUri 	 	= isset($_POST['baseUri']) ? $_POST['baseUri'] : null;

$requestHeaders	= isset($_POST['requestHeaders']) ? $_POST['requestHeaders'] : null;
$headersArray 	= explode("\n", $requestHeaders);

$requestPath 	= isset($_POST['requestPath']) ? $_POST['requestPath'] : null;
$pathEnd 	 	= strpos($requestPath, '?');
parse_str(parse_url($requestPath, PHP_URL_QUERY), $requestParams);

$webservice = new Yetti\API\Webservice();
$webservice->setAccessKey($accessKey);
$webservice->setPrivateKey($privateKey);
$webservice->setRequestMethod($method);
$webservice->setPostData($postData);
$webservice->setBaseUri($baseUri);
$webservice->setRequestPath($pathEnd ? substr($requestPath, 0, $pathEnd) : $requestPath);

if (is_array($headersArray))
{
	foreach ($headersArray as $header) {
		$webservice->addRequestHeader($header);
	}
}

foreach ($requestParams as $name => $value) {
	$webservice->setRequestParam($name, $value);
}

try {
	$webservice->makeRequest();
}
catch (Exception $e) {}

$response  = $webservice->getRequestMethod() . ' ' . str_replace($baseUri, '', $webservice->getRequestUri()) . " HTTP/1.1\n";
$response .= 'Host: ' . str_replace(array('http://', 'https://'), '', $baseUri) .  "\n";

if (is_array($headersArray))
{
	foreach ($headersArray as $header) {
		$response .= $header . "\n";
	}
}

$response .= 'X-Authorization: ' . $accessKey . ':' . $webservice->getRequestSignature() . "\n";

if (strlen($postData) && ($method == 'POST' || $method == 'PUT'))
{
	$response .= 'Content-Length: ' . strlen($postData) . "\n";
	$response .= "Content-Type: application/x-www-form-urlencoded\n";
	$response .= $postData . "\n";
}

$response .= "\n";
$response .= $webservice->getRawResponse();

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
			form fieldset ul li.float {
				float: left;
				width: 50%;
			}
			form fieldset label {
				display: block;
				margin-bottom: 3px;
			}
			input[type="text"] {
				width: 600px;
			}
			textarea {
				width: 100%;
				height: 600px;
				font-family: courier;
				font-size: 12px;
			}
			textarea[name="requestHeaders"] {
				height: 100px;
			}
			textarea[name="postData"] {
				height: 470px;
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
						<label>HTTP Method: </label>
						<select name="method">
							<option <?php if ($method=='GET')	 { echo 'selected="selected"'; } ?>>GET</option>
							<option <?php if ($method=='POST')	 { echo 'selected="selected"'; } ?>>POST</option>
							<option <?php if ($method=='PUT')  	 { echo 'selected="selected"'; } ?>>PUT</option>
							<option <?php if ($method=='DELETE') { echo 'selected="selected"'; } ?>>DELETE</option>
						</select>
					</li>
					
					<li class="float">
						<label>Request headers (one per line, optional): </label>
						<textarea name="requestHeaders"><?php echo htmlentities($requestHeaders); ?></textarea>
						
						<label>POST data (optional): </label>
						<textarea name="postData"><?php echo htmlentities($postData); ?></textarea>
					</li>
					
					<li class="float">
						<label>Request / Response (leave blank): </label>
						<textarea name="response"><?php echo htmlentities($response); ?></textarea>
					</li>
				</ul>
				
				<button type="submit">Make request</button>
			</fieldset>
		</form>
	</body>
</html>
