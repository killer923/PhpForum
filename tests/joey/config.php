<?php
require_once 'library/facebook.php';

$app_id = "__SECRET-APP-ID__";
$app_secret = "__SECRET-APP-SECRET__";

$facebook = new Facebook(array(
	'appId' => $app_id,
	'secret' => $app_secret,
	'cookie' => true
));

if(is_null($facebook->getUser()))
{
	header("Location:{$facebook->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos,email'))}");
	exit;
}
$logout=$facebook->getLogoutUrl(array(),'http://forum.abhibhatia.co.cc');