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
$success = 0;
if($is_mongo) {
	$m = new MongoClient();
	$db = $m->chrome_record;
	$collection_map = [];
	foreach($object as $v) {
		$v['time'] = time();
		$v['ua'] = $_SERVER['HTTP_USER_AGENT'];
		if(!isset($collection_map[$v['type']])) {
			$collection_map[$v['type']] = $db->$v['type'];
		}
		$collection_map[$v['type']]->insert($v);
		$success++;
	}
} else {
	$pdo = new PDO("mysql:host=127.0.0.1;dbname=chrome_record;charset=UTF8;", "root", "123456");
	$pdo->exec("set NAMES UTF8");
	$table = array();
	foreach($object as $v) {
		$v['time'] = time();
		$v['ua'] = $_SERVER['HTTP_USER_AGENT'];
		if(!isset($table[$v['type']])) {
			$table[$v['type']] = array();
		}
		$v['detail'] = json_encode($v['detail'], JSON_UNESCAPED_UNICODE);
		$table[$v['type']][] = $v;
	}
	foreach($table as $key => $value) {
		$sql = "INSERT INTO `{$key}` (`time`, `url`, `requestId`, `type`, `detail`, `ua`) VALUES \n";
		$item_list = array();
		foreach($value as $item) {
			/**
			 * @var int    $time
			 * @var string $url
			 * @var string $requestId
			 * @var string $type
			 * @var string $detail
			 * @var string $ua
			 */
			extract($item);
			$time = $pdo->quote($time, PDO::PARAM_INT);
			$url = $pdo->quote($url, PDO::PARAM_STR);
			$requestId = $pdo->quote($requestId, PDO::PARAM_STR);
			$type = $pdo->quote($type, PDO::PARAM_STR);
			$detail = $pdo->quote($detail, PDO::PARAM_STR);
			$ua = $pdo->quote($ua, PDO::PARAM_STR);
			$item_list[] = "({$time},{$url},{$requestId},{$type},{$detail},{$ua})";
		}
		$success += $pdo->exec($sql.implode(",\n", $item_list).";");
	}
}
header("Content-Type: text/plain; charset=utf-8");
echo "success:", $success;