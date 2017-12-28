<?php
session_start();
if($_SESSION['log_status']!=1){//not logged in
	echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first'>";
	exit(1);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head><title>WELCOME <?php 
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);

$query=sprintf("SELECT name FROM users WHERE uname='%s'",mysql_real_escape_string($_SESSION['uname']));
$result=mysql_query($query);
$row=mysql_fetch_array($result, MYSQL_ASSOC);
echo "".$row['name']."";?></title>
<link rel="stylesheet" type="text/css" href="data/css/default.css" media=screen>
<link rel="stylesheet" type="text/css" href="data/css/table.css" media=screen>
<link rel="stylesheet" type="text/css" href="data/css/print.css" media=print>
</head>
<body>
	<?php
	if (isset($_GET['msg'])) { 
	$msg=strip_tags($_GET['msg']);
	echo "<font color=\"black\"><h3 align=\"center\">".$msg." </h3></font>\n"; }
	?>
	<div id="header">
		<p align=right><a href=home.php>HOME</a> <a href="user.php?user=<?php echo$_SESSION['uname'];?>">Profile</a> <a href=newpass.php>Settings</a> <a href=logout.php>Log Out</a></p>
	</div>
	<div id="main">	
	<p>HIya <?php echo $row['name'];?></p><br>
	<a href=npost.php>New Post</a><br>
	<?php
	
	$query=mysql_query("SELECT * FROM `db_name`.`post` WHERE `lock`=1 ORDER BY date DESC LIMIT 5")or die('ERROR:Problem during showing recent posts');
	echo"\nLocked Posts\n";
	echo "<table style='width:100%' border=5 bordercolor=#5DACC50 bordercolorlight=#A25A50A bordercolordark=#387B0A7>\n\t<tr>\n\t\t<th>SR.No</th>\n\t\t<th>TITLE</th>\n\t\t<th>THREADS</th>\n\t\t<th>DATE n TIME</th>\n\t\t<th>POST ID</th>\n\t\t<th>CATEGORY</th>\n\t\t<th>ACTIONS</th>\n\t</tr>";
	$num=mysql_num_rows($query);
	for($i=1;$i<($num+1);$i++){
		$posts=mysql_fetch_array($query, MYSQL_ASSOC);
		$a=date('Y-m-d H:i:s',strtotime($posts['date']));
		if($posts['lock'])echo "\n\t<tr>\n\t\t<td>".$i."</td>\n\t\t<td><a href=posts.php?pid=".$posts['post_id'].">".$posts['title']."</a></td>\n\t\t<td>".$posts['tnum']."</td>\n\t\t<td>".$a."</td>\n\t\t<td>".$posts['post_id']."</td>\n\t\t<td>".$posts['Category']."</td>\n\t\t<td><a href=\"posts.php?pid=".$posts['post_id']."\">VIEW</a></td>\n\t</tr>";
		}
	echo "\n</table>\n<br>";
	$nquery=mysql_query("SELECT * FROM `db_name`.`post` WHERE `lock`=0 ORDER BY date DESC LIMIT 10")or die('ERROR:Problem during showing recent posts');	
	echo "\nRECENT POSTS\n";
	echo "<div class=\"post\">";
	echo "<table style='width:100%' border=5 bordercolor=#5DACC50 bordercolorlight=#A25A50A bordercolordark=#387B0A7 >\n\t<tr>\n\t\t<th width=5%>SR.No</td>\n\t\t<th width=20%>TITLE</td>\n\t\t<th width=10%>THREADS</td>\n\t\t<th width=20%>DATE n TIME</td>\n\t\t<th width=10%>POST ID</td>\n\t\t<th width=15%>CATEGORY</td>\n\t\t<th width=20%>ACTIONS</td>\n\t</tr>";
	$num=mysql_num_rows($nquery);
	for($i=1;$i<($num+1);$i++){
	$posts=mysql_fetch_array($nquery, MYSQL_ASSOC);
	$a=$a=date('Y-m-d H:i:s',strtotime($posts['date']));
	echo "\n\t<tr>\n\t\t<td>".$i."</td>\n\t\t<td><a href=posts.php?pid=".$posts['post_id'].">".$posts['title']."</a></td>\n\t\t<td>".$posts['tnum']."</td>\n\t\t<td>".$a."</td>\n\t\t<td>".$posts['post_id']."</td>\n\t\t<td>".$posts['Category']."</td>\n\t\t<td><a href=posts.php?pid=".$posts['post_id'].">VIEW</a><br><a href=\"entry.php?pid=".$posts['post_id']."\">REPLY</a></td>\n\t</tr>";
	}
	echo "\n</table>";
	mysql_close($dbc);
	?>
	</div>
	<div id=url>
		<script language="javascript">document.write("URL="+window.location);</script>
	<div id=url>
	</div>
</body>
</html>