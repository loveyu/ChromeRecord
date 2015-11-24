<?php
/**
 * User: loveyu
 * Date: 2015/11/23
 * Time: 0:08
 */
error_reporting(E_ALL);
$request = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents("php://input");
if(empty($request)) {
	return;
}
$object = json_decode($request, true);
if(!is_array($object) || empty($object) || !isset($object[0])) {
	return;
}
$time = time();
$is_mongo = false;
$db = null;
if($is_mongo) {
	$m = new MongoClient();
	$db = $m->chrome_record;
	$collection_map = [];
} else {
	file_put_contents("E:\\cr.log", print_r($object, true));
}
foreach($object as $v) {
	$object['time'] = time();
	$object['ua'] = $_SERVER['HTTP_USER_AGENT'];

	if($is_mongo) {
		if(!isset($collection_map[$v['type']])) {
			$collection_map[$v['type']] = $db->$object['type'];
		}
		$collection_map[$v['type']]->insert($v);
	}
}
echo "OK";
exit;