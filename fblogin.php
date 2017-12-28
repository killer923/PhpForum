<?php
require_once 'tests/joey/library/facebook.php';

$app_id = "152497421494367";
$app_secret = "a97375b5499ef1e42aff802d79f4cd13";

$facebook = new Facebook(array(
	'appId' => $app_id,
	'secret' => $app_secret,
	'cookie' => true
));

/*The parameters:
   * - next: the url to go to after a successful login
   * - cancel_url: the url to go to after the user cancels
   * - req_perms: comma separated list of requested extended perms
   * - display: can be "page" (default, full page) or "popup"
*/
$cancel	='http://test.abhibhatia.co.cc/forum/tests/fb/cancel.html';
//$next	='http://forum.abhibhatia.co.cc/fb/login';
$next='http://forum.abhibhatia.co.cc/fblogin2.php';
$display='popup';
echo "please wait....<br>";
if(is_null($facebook->getUser()))
{
	$perms=array('req_perms' => 'user_status,publish_stream,user_photos,email,status_update,friends_status,export_stream');
	echo"<a href=".$facebook->getLoginUrl($perms,$cancel,$next,$display).">Login</a>";
	exit;
}
else{
	echo"<meta http-equiv='refresh' content='0; URL= http://forum.abhibhatia.co.cc/login.php?type=fb'>";
}
$_SESSION['log_status']=1;
$_SESSION['logout_url']=$logout;
