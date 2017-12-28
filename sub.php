<?php
session_start();
if($_SESSION['log_status']!=1){ //not logged in
	echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first&&continue=sub.php?pid=".strip_tags($_GET['pid'])."&action=determine>";
	exit(1);
	}
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);

if(!isset($_SESSION['email'])){
	if(isset($_SESSION['fbmail'])){
		$_SESSION['email']=$_SESSION['fbmail'];
		}
	else{
		echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".strip_tags($_GET['pid'])."&msg=invalid user'>";
		mysql_close($dbc);
		exit();
		}
	}
$action=strip_tags($_GET['action']);
$c="SELECT subscribers FROM `post` WHERE post_id=".strip_tags($_GET['pid']);
$c=mysql_query($c) or die("not able to connect");
if(mysql_num_rows($c)==0){
	echo "<meta http-equiv='refresh' content='0; URL=posts.php?msg=Invalid post id'>";
	mysql_close($dbc);
	exit();
}
$subscribers=mysql_fetch_array($c, MYSQL_ASSOC);
if($action==0||$action=='determine'){
	$s=",";
	$list=explode($s,$subscribers['subscribers']);
	$exist=-1;
	for($i=0;;$i++){
		if($list[$i]==NULL) break;
		if($list[$i]==$_SESSION['email']) {$exist=$i;break;}
	}
	if($action=='determine'){
		if($exist==-1) $action=1;
		else echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".$pid."&msg=you are already subscribed'>";
		}
	if($action==0){
		if($exist==0){
			$nlist=$list[1];
			for($j=2;$j<$i;$j++){
				if($j!=$exist)
					$nlist=$nlist.",".$list[$j];
				}
			}
		else if($exist!=-1){
			$nlist=$list[0];
			for($j=1;$j<$i;$j++){
				if($j!=$exist)
					$nlist=$nlist.",".$list[$j];
				}
			}
		}
	}
if($action==1){
	if($subscribers['subscribers']!=NULL)	{
		if(isset($_SESSION['email']))
		$nlist=$subscribers['subscribers'].",".$_SESSION['email'];
		elseif(isset($_SESSION['fbmail']))
		$nlist=$subscribers['subscribers'].",".$_SESSION['fbmail'];
	}
	else{
	if(isset($_SESSION['email']))	$nlist=$_SESSION['email'];
	elseif(isset($_SESSION['fbmail'])) $nlist=$_SESSION['fbmail'];
	}
	$query= sprintf("UPDATE `db_name`.`post` SET `subscribers`='%s' WHERE `post`.`post_id`=%s LIMIT 1",mysql_real_escape_string($nlist),mysql_real_escape_string(strip_tags($_GET['pid'])));
	mysql_query($query) or die("not able to subscribe");
	$pid=strip_tags($_GET['pid']);
	echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".$pid."&msg=you have been subscribed'>";
	mysql_close($dbc);
	exit();
}
	$query= sprintf("UPDATE `db_name`.`post` SET `subscribers`='%s' WHERE `post`.`post_id`=%s LIMIT 1",mysql_real_escape_string($nlist),mysql_real_escape_string(strip_tags($_GET['pid'])));
	mysql_query($query) or die("not able to unsubscribe");
	mysql_close($dbc);
	echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".strip_tags($_GET['pid'])."&msg=you have been unsubscribed'>";
	exit();
	
/*echo "this is i=".$i;
$mail=$subscribers['subscribers'];
$mail=$list[1];
for($j=2;$j<$i;$j++){
$mail="".$mail.";".$list[$j];
}

echo $mail;*/
?>