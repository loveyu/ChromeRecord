<?php
/**
 * User: loveyu
 * Date: 2015/11/23
 * Time: 0:08
 */
error_reporting(E_ALL);
$request = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents("php://input");
if (empty($request)) {
    return;
}
$object = json_decode($request, true);
if (!is_array($object) || empty($object) || !isset($object['url'])) {
    return;
}
$m = new MongoClient();
$db = $m->chrome_record;
$collection = $db->$object['type'];
$object['time'] = time();
$object['ua'] = $_SERVER['HTTP_USER_AGENT'];
$collection->insert($object);