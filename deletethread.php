<?php
session_start();
if (isset($_GET['msg'])) { echo "<font color=\"black\"><h3 align=\"center\">".strip_tags($_GET[msg])." </h3></font>"; }
if($_SESSION['log_status']!=1){//not logged in
	echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first'>";
	exit(1);
	}

$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);

echo 'Please Wait';
$pid=strip_tags($_GET["pid"]);
$date=strip_tags($_GET["dt"]);
$check=mysql_query("SELECT `by` FROM threads WHERE pid='".$pid."' AND date='".$date."' LIMIT 1")or die('ERROR:Problem during  query');
if(mysql_num_rows($check)==0){
	echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".$pid."&msg=Invalid post";
	exit();
	}
$row=mysql_fetch_array($check,MYSQL_ASSOC);
if($row['by']!=$_SESSION['uname']){
	echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".$pid."&msg=You do not have rights'>";
	exit(1);
	}
$query="DELETE FROM `db_name`.`threads` WHERE `threads`.`pid` = ".$pid." AND `threads`.`by` = '".$row['by']."' AND `threads`.`date` = '".$date."' LIMIT 1";
$check=mysql_query($query);	
$check=mysql_query("SELECT `by` FROM threads WHERE pid='".$pid."'")or die('ERROR:Problem during query');
$tnum=mysql_num_rows($check);
$query=sprintf("UPDATE `db_name`.`post` SET `tnum`='%s' WHERE post_id='%s' LIMIT 1",mysql_real_escape_string($tnum),mysql_real_escape_string($pid));
mysql_query($query);
echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".$pid."&msg=Thread Successfully Deleted'>";
mysql_close($dbc);
exit(1);
?>