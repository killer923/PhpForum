<?php
session_start();
echo "Please Wait";
//set log_status=0 in users table
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);
$query=sprintf("UPDATE `db_name`.`users` SET `log_status`='0' WHERE uname='%s' LIMIT 1",mysql_real_escape_string($_SESSION['uname']));
mysql_query($query) or die("ERROR:Retry Later2");
mysql_close($dbc);
if($_SESSION['fb']==1){
	//echo"hi";
	//$logout=$facebook->getLogoutUrl(array(),'http://forum.abhibhatia.co.cc/enter.php?msg=Logged Out Successfully');
	echo "<meta http-equiv='refresh' content='0; URL=".$_SESSION['logout']."'>";
	session_destroy();
	exit();
	}
session_destroy();
echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Logged Out Successfully'>";
exit();
?>