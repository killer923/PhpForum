<?php
session_start();
if (isset($_GET['msg'])) { echo "<font color=\"black\"><h3 align=\"center\">".strip_tags($_GET[msg])." </h3></font>"; }
if($_SESSION['log_status']!=1){//not logged in
	echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first'>";
	exit(1);
	}

$heading=strip_tags($_REQUEST["title"]);
if(strlen($heading)>100){	//checks if alphanumeric
	echo "<meta http-equiv='refresh' content='0; URL=npost.php?msg=Title of post must be less than 100 characters long'>";
	exit(1);
	}
$category=strip_tags($_REQUEST["cat"]);	
$content=strip_tags($_REQUEST["message"],"<b><i><a><br>");
$c=explode("\n",$content);
$content=implode("<br>",$c);


$dbc= mysql_connect('db_host','username','password') or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);
$query=sprintf("INSERT INTO `db_name`.`post` (`title`, `post_id`, `tnum` , `date`, `Category`) VALUES ('%s', NULL, '1' , CURRENT_TIMESTAMP, '%s')",mysql_real_escape_string($heading),mysql_real_escape_string($category));
$result=mysql_query($query) or die("ERROR");

$pid=mysql_insert_id();

$query="INSERT INTO `threads` (`pid`,`content`,`by`,`date`)"."VALUES ('".$pid."', '".$content."','".$_SESSION["uname"]."', CURRENT_TIMESTAMP)";
$result=mysql_query($query) or die('ERROR:Problem during posting');

mysql_close($dbc);
echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".$pid."'>";

//echo "<meta http-equiv='refresh' content='0; URL=entry.php?pid=".$pid."'>";

?>