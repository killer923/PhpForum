<?php
session_start();
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);

if(!isset($_SESSION['email'])) {
	if(isset($_SESSION['fbmail'])) $_SESSION['email']=$_SESSION['fbmail'];
	}
?>
<html>
<head><title><?php if (isset($_GET['pid'])) {
						$pid=strip_tags($_GET['pid']);
						$query=sprintf("SELECT * FROM post WHERE post_id=%s",mysql_real_escape_string($pid));
						$result=mysql_query($query) or die("ERROR:Problem during fetching name");
						if(mysql_num_rows($result)==0){
							echo "</title></head><body>";
							if(isset($_GET['msg'])){
								echo "<meta http-equiv='refresh' content='0; URL=posts.php?msg=Invalid post id'>";
								mysql_close($dbc);
								exit();}
							else{
								echo "<meta http-equiv='refresh' content='0; URL=posts.php?msg=Invalid post id'>";
								mysql_close($dbc);
								exit();
								}
							}
						$row=mysql_fetch_array($result, MYSQL_ASSOC);
						$list=explode(",",$row['subscribers']);
						$exist=-1;
						for($i=0;;$i++){
							if($list[$i]==NULL) break;
							if($list[$i]==$_SESSION['email']) $exist=$i;
						}
						echo $row['title'];echo"</title>\n";
						echo"\n\t<link rel='alternate' type='application/rss+xml' title='".$row['title']." -- RSS' href='http://forum.abhibhatia.co.cc/feed.php?pid=".$pid."' />\n";	
						} 
						else echo"POSTS</title>";
						?>
			<link rel="stylesheet" type="text/css" href="data/css/default.css" media=screen>
			<link rel="stylesheet" type="text/css" href="data/css/table.css" media=screen>
			<link rel="stylesheet" type="text/css" href="data/css/print.css" media=print>
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
</head>
<body>

<?php
if($_SESSION['log_status']!=1){//not logged in
	if(isset($_GET['pid'])) {
		echo "Please <a href=index.php?continue=posts.php?pid=".$_GET['pid'].">LOG IN</a> or <a href=nuser.php>REGISTER</a> to post on this forum<br><br>";
		}
	else{	
		echo "Please <a href=index.php?continue=posts.php>LOG IN</a> or <a href=nuser.php>REGISTER</a> to post on this forum<br><br>";
		}
	}


	if (isset($_GET['msg'])) { 
	$msg=strip_tags($_GET['msg']);
	echo "<font color=\"black\"><h3 align=\"center\">".$msg." </h3></font>\n"; }
	if($_SESSION['log_status']==1){
		echo "
	<div id=\"header\">
		<p align=right><a href=home.php>HOME</a> <a href=\"user.php?user=".$_SESSION['uname']."\">Profile</a> <a href=newpass.php>Settings</a> <a href=logout.php>Log Out</a></p>
	</div>";
	}
	echo "<div id=\"main\">\n";
if (isset($_GET['pid'])) { 
	if(isset($_GET['see'])){$show=strip_tags($_GET['see']);}
	$query=sprintf("SELECT * FROM threads WHERE pid=%s",mysql_real_escape_string($pid));
	$result=mysql_query($query) or die("ERROR:Problem during fetching database");
	$num=mysql_num_rows($result);
	if(isset($_GET['see'])){
		if($show==all){
			$view=$num;
		} 
		else $view=strip_tags($_GET['see']);
	}
	else $view=20;
	$query=sprintf("SELECT * FROM `threads` WHERE pid=%s ORDER BY date DESC LIMIT %s",mysql_real_escape_string($pid),mysql_real_escape_string($view));
	$result=mysql_query($query) or die('ERROR:Problem during showing recent posts');
	$vnum=mysql_num_rows($result);
	if($vnum>$view) $vnum=$view;
	echo"<p>";
	if($row['lock']==1) echo"<b>THIS POST IS LOCKED</b><br>";
	echo "This Post has ".$num." threads.You are viewing ".$vnum." threads.</p>RECENT REPLIES";
	echo "<table>\n\t<tr>\n\t\t<th>SR.No</th>\n\t\t<th>Content</th>\n\t\t<th>DATE n TIME</th>\n\t\t<th>BY</th>\n\t</tr>";
	for($i=1;$i<($vnum+1);$i++){
		$posts=mysql_fetch_array($result, MYSQL_ASSOC);
		$a=date('Y-m-d H:i:s',strtotime($posts['date']));
		echo "\n\t<tr>\n\t\t<td>".$i."</td>\n\t\t<td>".$posts['content']."</td>\n\t\t<td>".$a."</td>\n\t\t<td><a href=user.php?user=".$posts['by'].">".$posts['by']."</a></td>";
		if($_SESSION['log_status']==1){
			if($posts['by']==$_SESSION['uname']&&!$row['lock']){
				echo "\n\t\t<td><a href=\"deletethread.php?pid=".$pid."&dt=".$posts['date']."\">DELETE</a></td>";
				}
			}
		echo "\n\t</tr>";
	}
	echo "\n</table>\n<p><div class=links>";
	if($exist==-1){
		echo "<a href=sub.php?pid=".$pid."&action=1>SUBSCRIBE<a><br>\n";
		}
	else{
		echo "<a href=sub.php?pid=".$pid."&action=0>UNSUBSCRIBE<a><br>\n";
		} 
	echo "<a href=feed.php?pid=".$pid.">RSS FEED</a><br> ";
	if($row['lock']==0 || $_SESSION['uname']=='abhatia')echo "<a href=entry.php?pid=".$pid.">REPLY</a><br> ";
	if($num>20){
		if($show==all){
			echo "<a href=posts.php?pid=".$pid."&see=20>RECENT 20</a>";
			}
		else {
			echo "<a href=posts.php?pid=".$pid."&see=all>ALL THREADS</a>";
			}
	}
	echo "<br>\n<a href=posts.php>OTHER POSTS</a></div></p>\n";
	} 
else{
	$query=mysql_query("SELECT * FROM `db_name`.`post` WHERE `lock`=1 ORDER BY date DESC LIMIT 5")or die('ERROR:Problem during showing recent posts');
	echo"\nLocked Posts\n";
	echo "<table>\n\t<tr>\n\t\t<th>SR.No</th>\n\t\t<th>TITLE</th>\n\t\t<th>THREADS</th>\n\t\t<th>DATE n TIME</th>\n\t\t<th>POST ID</th>\n\t\t<th>CATEGORY</th>\n\t\t<th>ACTIONS</th>\n\t</tr>";
		$num=mysql_num_rows($query);
		for($i=1;$i<($num+1);$i++){
			$posts=mysql_fetch_array($query, MYSQL_ASSOC);
			$a=date('Y-m-d H:i:s',strtotime($posts['date']));
			if($posts['lock'])echo "\n\t<tr>\n\t\t<td>".$i."</td>\n\t\t<td><a href=posts.php?pid=".$posts['post_id'].">".$posts['title']."</a></td>\n\t\t<td>".$posts['tnum']."</td>\n\t\t<td>".$a."</td>\n\t\t<td>".$posts['post_id']."</td>\n\t\t<td>".$posts['Category']."</td>\n\t\t<td><a href=\"posts.php?pid=".$posts['post_id']."\">VIEW</a></td>\n\t</tr>";
			}
		echo "\n</table>\n<br>";
		
	$nquery=mysql_query("SELECT * FROM `db_name`.`post` WHERE `lock`=0 ORDER BY date DESC LIMIT 10")or die('ERROR:Problem during showing recent posts');	
	echo "\nRECENT POSTS\n";
	if($_SESSION['log_status']==1){
		echo "<table>\n\t<tr>\n\t\t<th>SR.No</th>\n\t\t<th>TITLE</th>\n\t\t<th>THREADS</th>\n\t\t<th>DATE n TIME</th>\n\t\t<th>POST ID</th>\n\t\t<th>CATEGORY</th>\n\t\t<th>ACTIONS</th>\n\t</tr>";
		$num=mysql_num_rows($nquery);
		for($i=1;$i<($num+1);$i++){
			$posts=mysql_fetch_array($nquery, MYSQL_ASSOC);
			$a=date('Y-m-d H:i:s',strtotime($posts['date']));
			echo "\n\t<tr>\n\t\t<td>".$i."</td>\n\t\t<td><a href=posts.php?pid=".$posts['post_id'].">".$posts['title']."</a></td>\n\t\t<td>".$posts['tnum']."</td>\n\t\t<td>".$a."</td>\n\t\t<td>".$posts['post_id']."</td>\n\t\t<td>".$posts['Category']."</td>\n\t\t<td><a href=\"posts.php?pid=".$posts['post_id']."\">VIEW</a><br><a href=\"entry.php?pid=".$posts['post_id']."\">REPLY</a></td>\n\t</tr>";
			}
		echo "\n</table>";
		}
	else{
		echo "<table border=1>\n\t<tr>\n\t\t<th>SR.No</th>\n\t\t<th>TITLE</th>\n\t\t<th>THREADS</th>\n\t\t<th>DATE n TIME</th>\n\t\t<th>POST ID</th>\n\t\t<th>CATEGORY</th>\n\t\t<th>ACTIONS</th>\n\t</tr>";
		$num=mysql_num_rows($nquery);
		for($i=1;$i<($num+1);$i++){
			$posts=mysql_fetch_array($nquery, MYSQL_ASSOC);
			$a=date('Y-m-d H:i:s',strtotime($posts['date']));
			echo "\n\t<tr>\n\t\t<td>".$i."</td>\n\t\t<td><a href=posts.php?pid=".$posts['post_id'].">".$posts['title']."</a></td>\n\t\t<td>".$posts['tnum']."</td>\n\t\t<td>".$a."</td>\n\t\t<td>".$posts['post_id']."</td>\n\t\t<td>".$posts['Category']."</td>\n\t\t<td><a href=\"posts.php?pid=".$posts['post_id']."\">VIEW</a></td>\n\t</tr>";
			}
		echo "\n</table>\n";
		}
}		
if($_SESSION['log_status']==1){
	echo "<br>\n<a href=logout.php>LOGOUT</a>\n";}
	mysql_close($dbc);
?>
	</div>
<div id=url>
	<script language="javascript">document.write("URL="+window.location);</script>
<div id=url>
</body>
</html>