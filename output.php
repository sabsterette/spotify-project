<?php

function printValues($arr) {
	global $count;
	global $values;
	if(!is_array($arr)) {
		die("Input not array");
	}
	foreach($arr as $key=>$value) {
		if (is_array($value)) {
			printValues($value);
		} else {
			$values[] = $value;
			$count++;
		}
	}
	return array('total'=> $count, 'values' => $values);		
}
echo "hello";
$tracksjson=file_get_contents("playlist.json");
$arr=json_decode($tracksjson, true);
$tracks=$arr['tracks']['items'];
foreach($tracks as $key=>$value) {
	foreach($value as $key2=>$value2) {	
		$moretracks=$value2['track'];
		var_dump($moretracks);
	}		
}
#var_dump($trackarr);
#$result = printValues($arr);
#echo $result["total"] . " values found:";
#echo implode("<br>", $result["values"]);

?>
