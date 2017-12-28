<?php
require_once 'tests/joey/config.php';
session_start(); 
?>
<html>
<head><title>Logging In..</title>
</head>
<body>
<p>Please Wait.....
</p>
<?php

/*
 	check if new user
	if exists login him
	else create and login
*/
//get details from FB
$user = $facebook->api('/me');
$emailid=$user['email'];
$username=$user['username'];
$fullname=$user['name'];
$_SESSION['fbmail']=$emailid;
$_SESSION['fullname']=$fullname;
$_SESSION['fbuname']=$username;
$_SESSION['logout']=$facebook->getLogoutUrl(array(),'http://forum.abhibhatia.co.cc/index.php?msg=Logged Out Successfully');

//check if user exists
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);

$query=sprintf("SELECT * FROM users WHERE email='%s' OR fbmail='%s'",mysql_real_escape_string($emailid),mysql_real_escape_string($emailid));
//echo $query;
$check=mysql_query($query)or die('ERROR:Problem during a query');
//$check=mysql_query("SELECT * FROM users WHERE email=".$emailid." OR fbmail=".$emailid)or die("ERROR:Problem during a query while checking");
if(mysql_num_rows($check)!=1){	//user email doesn't exist....ask if already have an account
	if($username==''){
	if(isset($_GET['continue'])){	echo"<meta http-equiv='refresh' content='0; URL=fb_connect.php?uname=none&continue=".strip_tags($_GET['continue'])."'>";}
	else{echo"<meta http-equiv='refresh' content='0; URL=fb_connect.php?uname=none'>";}
	exit();
	}
}
else{					//user exists
$row=mysql_fetch_array($check, MYSQL_ASSOC);
$_SESSION['log_status']=1;
	if($row['email']==$_SESSION['fbmail']||$row['email']==''){
		$_SESSION['email']=$_SESSION['fbmail'];
	}
	elseif($row['fbmail']==$_SESSION['fbmail']&&$row['email']!=''){
		$_SESSION['email']=$row['email'];
	}
$query= sprintf("UPDATE users SET `acc_status` = '1', `log_status` = '1' WHERE `users`.`name` = '%s' AND `users`.`uname` = '%s' AND `users`.`psswd` = '%s' AND `users`.`email` = '%s' AND `users`.`acc_status` = '%s' AND `users`.`log_status` = '%s' LIMIT 1;",mysql_real_escape_string($row["name"]),mysql_real_escape_string($row["uname"]),mysql_real_escape_string($row["psswd"]),mysql_real_escape_string($row["email"]),mysql_real_escape_string($row["acc_status"]),mysql_real_escape_string($row["log_status"]));
mysql_query($query) or die("not able to update");
$_SESSION['uname']=$row['uname'];
}

if(isset($_GET['continue'])) echo "<meta http-equiv='refresh' content='0; URL=".$_GET['continue']."'>";	
else echo "<meta http-equiv='refresh' content='0; URL=home.php'>";	


mysql_free_result($check);
mysql_close($dbc);
exit();
?>

</body>
</html>
