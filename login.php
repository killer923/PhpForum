<?php
session_start();
require_once 'tests/joey/library/facebook.php';
require_once('tests/twitter/twitteroauth/twitteroauth.php');
require_once('tests/twitter/config.php');
if($_SESSION['log_status']==1){//already logged in
	echo "<script language=javascript>window.location='home.php';</script>";
	exit(1);
	}
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);

if(isset($_GET['type'])){
	if($_GET['type']=='fb'){
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
		if(isset($_GET['continue'])){
			$next='http://forum.abhibhatia.co.cc/fblogin2.php?continue='.$_GET['continue'];
		}
		else{
			$next='http://forum.abhibhatia.co.cc/fblogin2.php';
		}
		$display='popup';
		try{
			$me = $facebook->api('/me');
			$error='no';
		}
		catch (FacebookApiException $e) {
			$error='yes';
			$perms=array('req_perms' => 'user_status,publish_stream,user_photos,email,status_update,friends_status,export_stream');
			echo"<meta http-equiv='refresh' content='0; URL=".$facebook->getLoginUrl($perms,$cancel,$next,$display)."'>";
			exit;			
		  }
		if($error!='yes'){
			//echo"<script language=javascript>alert('hi');</script>";
			echo "please wait....<br>";
			$emailid=$me['email'];
			//echo $emailid;
			$query=sprintf("SELECT * FROM users WHERE email=\"%s\" OR fbmail=\"%s\"",mysql_real_escape_string($emailid),mysql_real_escape_string($emailid));
			$check=mysql_query($query)or die('ERROR:Problem during a query00');
			if(mysql_num_rows($check)!=1){	//user email doesn't exist....ask if already have an account
				if(isset($_GET['continue']))
					echo "<script language=javascript>top.location='fb_connect.php?continue=".strip_tags($_GET['continue'])."';</script>";
				else 
					echo "<script language=javascript>top.location='fb_connect.php';</script>";
				exit();
			}
			$row=mysql_fetch_array($check, MYSQL_ASSOC);
			$_SESSION['log_status']=1;
			$_SESSION['uname']=$row['uname'];
			$_SESSION['email']=$row['email'];
			$_SESSION['fb']=1;
			$_SESSION['logout']=$facebook->getLogoutUrl(array(),'http://forum.abhibhatia.co.cc/index.php?msg=Logged Out Successfully');
			if(isset($_GET['continue'])) echo "<meta http-equiv='refresh' content='0; URL=".$_GET['continue']."'>";	
			else echo "<meta http-equiv='refresh' content='0; URL=home.php'>";	
			}		
			exit();
	}
	elseif($_GET['type']=='twitter'){
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
			$url='tests/twitter/clearsessions.php';
			echo"Please Wait....";
			echo"<script language=javascript>top.location='".$url."';</script>";
			exit;
			}
		else{
			function objectToArray($object)
				{
					$array=array();
					foreach($object as $member=>$data)
					{
						$array[$member]=$data;
					}
					return $array;
				}
			/* Get user access tokens out of the session. */
			$access_token = $_SESSION['access_token'];

			/* Create a TwitterOauth object with consumer/user tokens. */
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

			/* If method is set change API call made. Test is called by default. */
			$content = $connection->get('account/verify_credentials');

			//Get screen name of the user
			$a=objectToArray($content);
			$screen_name=$a['screen_name'];
			//check database
			$query=sprintf("SELECT * FROM users WHERE twitter_name='%s'",mysql_real_escape_string($screen_name));
			$check=mysql_query($query)or die('ERROR:Problem during a query');
			/*
			if(mysql_num_rows($check)!=1){	//user email doesn't exist....ask if already have an account
				echo"<meta http-equiv='refresh' content='0; URL=twitter_connect.php'>";
				exit();
			}*/
			$_SESSION['all']=$a;
			if(mysql_num_rows($check)!=1){	//user email doesn't exist....ask if already have an account
				if(isset($_GET['continue']))
					echo "<script language=javascript>top.location='twitter_connect.php?continue=".strip_tags($_GET['continue'])."';</script>";
				else 
					echo "<script language=javascript>alert('".$screen_name."');top.location='twitter_connect.php';</script>";
				exit();
			}
			$row=mysql_fetch_array($check, MYSQL_ASSOC);
			$_SESSION['log_status']=1;
			$_SESSION['email']=$row['email'];
			$_SESSION['uname']=$row['uname'];
			if(isset($_GET['continue'])) echo "<meta http-equiv='refresh' content='0; URL=".$_GET['continue']."'>";	
			else echo "<meta http-equiv='refresh' content='0; URL=home.php'>";
			exit;
		}
	}
}
		
$user=strip_tags($_POST['username']);
$pwd=strip_tags($_POST['password']);
$query=sprintf("SELECT * FROM users WHERE uname='%s'",mysql_real_escape_string($user));
$check=mysql_query($query);
if(mysql_num_rows($check)!=1){
	if(isset($_GET['continue']))
		echo "<meta http-equiv='refresh' content='0; URL=enter.php?msg=Username not found&continue=".$_GET['continue']."'>";	
	else echo "<meta http-equiv='refresh' content='0; URL=enter.php?msg=Username not found'>"; 	
	exit();
	}
$row=mysql_fetch_array($check, MYSQL_ASSOC);
if((md5($pwd))!=$row["psswd"]){
	if(isset($_GET['continue']))
		echo "<meta http-equiv='refresh' content='0; URL=enter.php?msg=Username and Password do not match&continue=".$_GET['continue']."'>";	
	else echo "<meta http-equiv='refresh' content='0; URL=enter.php?msg=Username and Password do not match'>";	
	exit();
	}
$_SESSION['log_status']=1;
$_SESSION['email']=$row['email'];
$query= sprintf("UPDATE users SET `acc_status` = '1', `log_status` = '1' WHERE `users`.`name` = '%s' AND `users`.`uname` = '%s' AND `users`.`psswd` = '%s' AND `users`.`email` = '%s' AND `users`.`acc_status` = '%s' AND `users`.`log_status` = '%s' LIMIT 1;",mysql_real_escape_string($row["name"]),mysql_real_escape_string($row["uname"]),mysql_real_escape_string($row["psswd"]),mysql_real_escape_string($row["email"]),mysql_real_escape_string($row["acc_status"]),mysql_real_escape_string($row["log_status"]));
mysql_query($query) or die("not able to update");
$_SESSION['uname']=$user;

if($row["acc_status"]==0){
	$_SESSION['new_user']=1;//first login
	?>
<html>
<head>
<link rel="stylesheet" href="data/css/settings.css" type="text/css">
<script language="javascript" src="data/scripts/settings.js"></script>
</head>
<body>
<?php
	echo "Welcome<br><br><br>We strongly recommend you to change your password and set security question immediately<br>";
?>	
<form action="changepass.php" method="post" name="setting">
<table>
	<tr>
		<td>Change Password:</td>
		<td><input type="radio" name="pass" onClick="show();" value="1" id="Yes"><label for="Yes">Yes</label></td>
		<td><input type="radio" name="pass" onClick="hide();" value="0" id ="No" CHECKED><label for="No">No</label><br></td>
	</tr>
	<tr>
		<td>Change Security Question:</td>
		<td><input type="radio" name="question" onClick="appear();" value="1" id="yes"><label for="yes">Yes</label></td>
		<td><input type="radio" name="question" onClick="disappear();" value="0" id="no" CHECKED><label for="no">No</label></td>
	</tr>
</table>
<div id="dynamic"></div>
<br><br>
<a href="home.php">Continue</a>
</body>
</html>
	<?php
	}

else{
	if(isset($_GET['continue'])) echo "<meta http-equiv='refresh' content='0; URL=".$_GET['continue']."'>";	
	else echo "<meta http-equiv='refresh' content='0; URL=home.php'>";	
	mysql_free_result($check);
	mysql_close($dbc);
	exit();
}
mysql_free_result($check);
mysql_close($dbc);
?>
