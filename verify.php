<?php
session_start();
if (isset($_GET['msg'])) { echo "<font color=\"black\"><h3 align=\"center\">".strip_tags($_GET[msg])." </h3></font>"; }
if($_SESSION['log_status']==1){//already logged in
	echo "<meta http-equiv='refresh' content='0; URL=home.php'>";
	exit(1);
	}
?>
<html>
<head>
<title>RESET PASSWORD</title>
</head>
<body>
<?php
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);

$_SESSION['uname']=strip_tags($_POST['user']);
$query="SELECT `question` FROM `users` WHERE `uname` = '".$uname."' LIMIT 1";
$check=mysql_query($query) or die('ERROR:Problem during query');
if(mysql_num_rows($check)==0){
	echo"<meta http-equiv='refresh' content='0; URL=forgot.php?msg=no such user'>";
	mysql_close($dbc);
	exit();}
$row=mysql_fetch_array($check,MYSQL_ASSOC);
if ($row['question']!=NULL){
	echo "<form action=\"reset.php?user=".$uname."\" method=\"post\">\n";
	echo "Q: \t".$row['question']."";
	echo "<br>\nEnter answer:<input type=\"password\" name=\"ans\" size=\"45\"><br>\n<input type=\"Submit\" name=\"Send\" value=\"VERIFY\"><br>\n</form>\n";
	}
else{
	echo "You have not set your security question\nPlease contact <a href=\"mailto:abhatia@iitk.ac.in\">administrator</a>.";
	}
mysql_close($dbc);
?>
</body>
</html>
