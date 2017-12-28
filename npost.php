<?php
session_start();
if (isset($_GET['msg'])) { echo "<font color=\"black\"><h3 align=\"center\">".strip_tags($_GET[msg])." </h3></font>"; }
if($_SESSION['log_status']!=1){//not logged in
	echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first&continue=npost.php'>";
	exit(1);
	}
?>

<html>
<head>
	<title>Create New Post</title>
	<link rel="stylesheet" href="data/css/default.css">
</head>
<body>
	<div id="header">
		<p align=right><a href=home.php>HOME</a> <a href="user.php?user=<?php echo$_SESSION['uname'];?>">Profile</a> <a href=newpass.php>Settings</a> <a href=logout.php>Log Out</a></p>
	</div>
	<div id="main">
		<table align="center">
		<form action="cpost.php" method="post">
			<tr>
				<td>Enter post title:</td>
				<td><input type="text" name="title" size="45"></td>
			</tr>
			<tr>
				<td>Enter post category :</td>
				<td><input type="text" name="cat" size="45"></td>
			</tr>
			<tr>
				<td>Enter content :</td>
				<td><textarea name="message" cols="150" rows="5" wrap="virtual"></textarea></td>
			</tr>
		</table>
		<input type="submit" name="send" value="Create Post"><br><br><br>
		</form>
	</div>
</body>
</html>