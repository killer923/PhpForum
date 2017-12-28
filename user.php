<?php
session_start();
if($_SESSION['log_status']!=1){//not logged 
	if(isset($_GET['user'])){ echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first&continue=user.php?user=".$_GET['user']."'>";}
	else {echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first&continue=user.php'>";}
	exit(1);
	}
$dbc= mysql_connect('db_host','username','password')
or die('ERROR:Problem Connecting server');
mysql_select_db("db_name", $dbc);

?>
<html>
<head>
	<title><?php if(isset($_GET['user']))
					{
					$query="SELECT `name` FROM `users` WHERE `uname`='".strip_tags($_GET['user'])."' LIMIT 1";
					$name=mysql_query($query);
					if(mysql_num_rows($name)==0){
						echo"Invalid Username</title></head><body>Invalid Username or User no longer exists on this forum<br><a href=home.php>GO HOME</a></body>";
						exit();
						}
					$name=mysql_fetch_array($name,MYSQL_ASSOC);
					$name=$name['name'];
					$uname=strip_tags($_GET['user']);
					}
				else {
					$query="SELECT `name` FROM `users` WHERE `uname`='".$_SESSION['uname']."' LIMIT 1";
					$name=mysql_query($query);
					$name=mysql_fetch_array($name,MYSQL_ASSOC);
					$name=$name['name'];
					$uname=$_SESSION['uname'];
					}
				echo $name;?></title>
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
<?php	//echo"<div id='ad'><script type=\"text/javascript\"><!--	\n			google_ad_client = \"ca-pub-9432378994594271\";				/* forum-sidebar */				google_ad_slot = \"2025630044\";				google_ad_width = 160;				google_ad_height = 600;			\n	//-->\n				</script>				\n<script type=\"text/javascript\"				src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">				</script>\n</div>\n";
	echo "<p align=center><b><font style=\"Comic Sans MS\" size=5>".$name."</font></b></p>";
	$query="SELECT * FROM threads WHERE `by`='".$uname."'";
	$replies=mysql_query($query)or die("PROBLEM OCCURRED");
	echo "This user has posted a total of ".mysql_num_rows($replies)." threads.";
	if(isset($_GET['see'])){$show=$_GET['see'];}
	if($show==all||mysql_num_rows($replies)<20){
		$view=mysql_num_rows($replies);
	}
	else $view=20;
	$query=sprintf("SELECT * FROM `threads` WHERE `by`='%s' ORDER BY date DESC LIMIT %s",mysql_real_escape_string($uname),mysql_real_escape_string($view));
	$replies=mysql_query($query) or die('PROBLEM');
?>
<table border=1>
<tr>
	<th>
		POST
	</th>
	<th>
		CONTENT
	</th>
	<th>
		DATE n TIME
	</th>
</tr>
<?php	
for($i=1;$i<$view+1;$i++){
	$reply=mysql_fetch_array($replies,MYSQL_ASSOC);
	$query="SELECT `title` FROM `post` WHERE `post_id`='".$reply['pid']."' LIMIT 1";
	$pname=mysql_query($query) or die("ERROR fetching post name");
	$pname=mysql_fetch_array($pname,MYSQL_ASSOC);
	echo"<tr>\n\t<td>\n\t\t<a href=posts.php?pid=".$reply['pid'].">".$pname['title']."</a></td>\n\t<td>\n\t\t".$reply['content']."\n\t</td>\n\t<td>\n\t\t".$reply['date']."\n\t</td>\n</tr>";
	}
echo "</table>";
if($show==all){
	echo "<a href=user.php?user=".$uname."&see=20>RECENT 20</a>";
	}
else {
	echo "<a href=user.php?user=".$uname."&see=all>ALL THREADS</a>";
	}
mysql_close($dbc);
	
?>
	</div>
</body>
</html>