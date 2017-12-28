<?php
session_start();
if($_SESSION['log_status']!=1){//already logged in
	echo 'Not logged in';
	echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first&continue=entry.php?pid=".$_GET['pid']."'>";
	exit(1);
	}
$pid=strip_tags($_GET["pid"]);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<title>Reply</title>
	<link rel="stylesheet" type="text/css" href="data/css/default.css" media=screen>

</head>
<body>
<?php
	if (isset($_GET['msg'])) { 
	$msg=strip_tags($_GET['msg']);
	echo "<font color=\"black\"><h3 align=\"center\">".$msg." </h3></font>\n"; }
	
if(ctype_digit($pid)==0){echo "<b><font size=5 align=center>invalid post id</b></font><br></body></html>";exit();}
	?>
	<div id="header">
		<p align=right><a href=home.php>HOME</a> <a href="user.php?user=<?php echo$_SESSION['uname'];?>">Profile</a> <a href=newpass.php>Settings</a> <a href=logout.php>Log Out</a></p>
	</div>
	<div id="main">
		<form action="creply.php?pid=<?php echo $pid;?>" method="post">
		<table>
		<tr>
			<td align=center>Enter post content :<br><font size=2>(Should be less than 5000 characters)</font></td>
			<td><textarea name="message" cols="150" rows="5" wrap="virtual"></textarea><br></td>
		</tr>
		</table>
		<input type="submit" name="send" value="submit"><br><br><br>
		</form>
	</div>
</body>
</html>