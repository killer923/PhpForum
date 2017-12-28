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
if(ctype_digit($pid)==0){echo "<b><font size=5 align=center>invalid post id</b></font><br>";}

$result=mysql_query("SELECT * FROM post WHERE post_id='".$pid."' LIMIT 1") or die('ERROR:Problem during showing recent posts');
$number=mysql_num_rows($result);
if($number==0) {
	echo "<meta http-equiv='refresh' content='0; URL=entry.php?msg=No such post exists'>";
	exit(1);
	}
$row=mysql_fetch_array($result);
if($row['lock']==1 && $_SESSION['uname']!='abhatia'){
	echo "<meta http-equiv='refresh' content='0; URL=posts.php?msg=The post is locked,You Cannot reply to it'>";
	exit(1);
	}
$content=strip_tags($_REQUEST["message"],"<b><i><a><br><code><pre><kbd>");
$c=explode("\n",$content);
$content=implode("<br>",$c);
$c=explode("<a",$content);
$content=implode("<a rel=nofollow",$c);
if(strlen($content)>5000){	//checks for length
	echo "<meta http-equiv='refresh' content='0; URL=entry.php?msg=your message must be  must be less than 5000 characters long'>";
	exit(1);
	}
$query="INSERT INTO `threads` (`pid`,`content`,`by`,`date`) VALUES ('".$pid."', '".$content."','".$_SESSION["uname"]."', CURRENT_TIMESTAMP)";
$result=mysql_query($query) or die('ERROR:Problem during posting');

$tnum=$row['tnum']+1;


$bcc=$row['subscribers'];
$subject="New post on ".$row['title'];
$message="".$row['title']."\n<br>By:".$_SESSION['uname']."\n<br>Content:".$content."\n\n<br><br>View post:'http://forum.abhibhatia.co.cc/posts.php?pid=".$pid."'\n<br><br>Regards\n<br>Forum@abhibhatia.co.cc";
$header="From:Forum<forum@forum.abhibhatia.co.cc>\r\nBcc:".$bcc."\r\nContent-type: text/html; charset=iso-8859-1\r\n";
mail(null,$subject,$message,$header);

$query= sprintf("UPDATE `db_name`.`post` SET `tnum`='%s' WHERE `post`.`post_id`='%s' LIMIT 1",mysql_real_escape_string($tnum),mysql_real_escape_string($row['post_id']));
mysql_query($query) or die("<meta http-equiv='refresh' content='0; URL=posts.php?pid=".$pid."&msg=WARNING:Number of threads was Not Updated.'>");
mysql_close($dbc);
echo "<meta http-equiv='refresh' content='0; URL=posts.php?pid=".$pid."'>";
exit();
?>