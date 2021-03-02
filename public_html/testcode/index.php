<?php 
	if (isset($_SERVER['HTTP_ORIGIN'])) {
	    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	    header('Access-Control-Allow-Credentials: true');
	    header('Access-Control-Max-Age: 86400');    // cache for 1 day
	}
	$str = '';
	// foreach($_POST as $val){
	// 	$str+=$val;
	// }
	$str .= $_POST['aaaa'];
	$str .= '_'.$_POST['test'].'<<';
	// echo (isset($_POST['test'])?$_POST['test']:'');
		$file = fopen($str.'_'.time().".txt","w");
		fwrite($file,$str.'<');
		fclose($file);
	// Access-Control headers are received during OPTIONS requests
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
	        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
	        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

	    exit(0);
	    
	}
	echo json_encode(array('1'=>1));
?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<form action="index.php" method="POST">
		<input type="text" name="test">
		<input type="submit">
	</form>
</body>
</html> -->