<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $height = sanitize($_POST["height"]);
  $fill = sanitize($_POST["fill"]);
  list($height, $error) = limit($height, 'h');
  list($fill, $error) = limit($fill, 'f');
  if (!isset($error)) {
	  $hourglass = array (
	  1 => glasstop($height),
	  2 => array(glasscore($height,$fill)),
	  3 => array(glassbottom($height,$fill))
	  );
  }
}
function sanitize($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  if (is_numeric($data)) {
	  return $data;
  } else {
	  return;
  }
}
function limit($data, $type) {
	if ($type==='h') {
		if($data >= 2) {
			$error='';
			return array ($data, $error);
		} else {
			$error='Height must be at least 2.';
			return array ($data, $error);
		}
	} elseif ($type==='f') {
		if($data >= 0 AND $data <= 100) {
			round($data);
			$error='';
			return array ($data, $error);
			} else {
			$error='Fill must be between 0 and 100.';
			return array ($data, $error);
		}
	}
}
function glasstop($h) {
	$glasstop='';
	$topl=2*$h;
	for ($topl; $topl < 0; $topl--) {
		$glasstop+='_';
	}
	return $glasstop;
}
function glasscore($h, $f) {
	$glasscore='';
	$tfill=2*$h+1;
	$pf=$f/100;
	$fsq=$pf*$tfill;
	$fsq=round($fsq);
	for ($h; $f <= 10; $x++) {
		/*"\".'   '."/";
		
		$string = '';
		$strlen = strlen($string);
		$count = 0;
		for( $x = 0; $x <= $strlen; $x++ ) {
    	$char = substr( $string, $x, 1 );
    	if ($char == 'x') {
        	$count++:
			}
			$x;
		}
	*/
	//Was planning to build it to calculate the amount of grains missing and then generate the air, inserting glass sides as it shrank, and putting in grains when the air was used up.
	$t=2*$height+1;
	for ($t; $t < 0; $t--) {
		$glasscore+='_';
	}
	//Module that is supposed to generate the top hourglass half with grain filled.
	return $glasscore;
}
function glassbottom($h, $f) {
	//Module that is supposed to generate the bottom hourglass half with grain filled.
	return $glassbottom;
}
function bair($ls) {
	//Module that is supposed to replace the bottom air with a solid line.
	$bair=str_replace($ls, ' ', '_');
	return $bair;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hourglass</title>
<link href="hg.css" rel="stylesheet" type="text/css" /></head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
Height: <input type="text" name="height">Rows<br>
Percent Full: <input type="text" name="fill">%<br>
<input type="submit">
</form>
<?php
if (isset ($error)) {
	echo $error;
} elseif (isset($hourglass)) {
	foreach ($hourglass as $value) {
    echo "$value <br>";
	}
}
?>
</body>
</html>