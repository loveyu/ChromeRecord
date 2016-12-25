<?php
/**
 * User: loveyu
 * Date: 2015/11/23
 * Time: 0:08
 */
error_reporting(E_ALL);
date_default_timezone_set("Asia/Shanghai");
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
	$view_tab = array();
	foreach($object as $v) {
		if($v['type'] == "onView") {
			$view_tab[] = [
				'tab_id'   => (int)$v['detail']['tab_id'],
				'url'      => $v['detail']['url'],
				'title'    => $v['detail']['title'],
				'type'     => $v['detail']['content_type'],
				'referrer' => $v['detail']['referrer'],
				'datetime' => date("Y-m-d H:i:s", $v['detail']['datetime'] / 1000).".".($v['detail']['datetime'] % 1000),
				'add_time' => time(),
				'uid'      => $v['detail']['uuid'],
			];
		} else {
			$v['time'] = time();
			$v['ua'] = $_SERVER['HTTP_USER_AGENT'];
			if(!isset($table[$v['type']])) {
				$table[$v['type']] = array();
			}
			$v['detail'] = json_encode($v['detail'], JSON_UNESCAPED_UNICODE);
			$table[$v['type']][] = $v;
		}
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

	if(!empty($view_tab)) {
		$view_sql = "INSERT INTO `onview` (`tab_id`, `url`, `title`, `referrer`, `datetime`, `add_time`, `type`, `uid`) VALUES ";
		$item_list = [];
		foreach($view_tab as $item) {
			$item['tab_id'] = $pdo->quote($item['tab_id'], PDO::PARAM_INT);
			$item['url'] = $pdo->quote($item['url'], PDO::PARAM_STR);
			$item['title'] = $pdo->quote($item['title'], PDO::PARAM_STR);
			$item['type'] = $pdo->quote($item['type'], PDO::PARAM_STR);
			$item['referrer'] = $pdo->quote($item['referrer'], PDO::PARAM_STR);
			$item['datetime'] = $pdo->quote($item['datetime'], PDO::PARAM_STR);
			$item['add_time'] = $pdo->quote($item['add_time'], PDO::PARAM_INT);
			$item['uid'] = $pdo->quote($item['uid'], PDO::PARAM_STR);
			$item_list[] = "({$item['tab_id']},{$item['url']},{$item['title']},{$item['referrer']},{$item['datetime']},{$item['add_time']},{$item['type']},{$item['uid']})";
		}
		echo $view_sql = $view_sql.implode(",\n", $item_list).";";
		$success += $pdo->exec($view_sql);
	}

}
header("Content-Type: text/plain; charset=utf-8");
echo "success:", $success;