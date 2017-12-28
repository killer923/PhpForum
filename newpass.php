<?php
session_start();
if($_SESSION['log_status']!=1){//not logged in
	echo "<meta http-equiv='refresh' content='0; URL=index.php?msg=Please Log in first&continue=newpass.php'>";
	exit(1);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF 8">
	<style>
		@import url("data/css/default.css");
		@import url("data/css/settings.css");
	</style>
	<script language="javascript" src="data/scripts/settings.js"></script>
	<noscript></head><body>You are Using an Outdated Browser.<br>Please Update Your Browser</body></noscript>
	<title>Settings</title>
</head>
<body>
<?php
	if (isset($_GET['msg'])) { echo "<font color=\"black\"><h3 align=\"center\">".strip_tags($_GET[msg])." </h3></font>"; }
?>
	<div id="header">
		<p align=right><a href=home.php>HOME</a> <a href="user.php?user=<?php echo$_SESSION['uname'];?>">Profile</a> <a href=newpass.php>Settings</a> <a href=logout.php>Log Out</a></p>
	</div>
	<div id="main">
		
		<form action="changepass.php" method="post" name="setting">
		<div class=options>
		<table>
			<tr>
				<td>Change Password:</td>
				<td><input type="radio" name="pass" onClick="show();" value="1" id="Y"><label for="Y">Yes</label></td>
				<td><input type="radio" name="pass" onClick="hide();" value="0" id ="N" CHECKED><label for="N">No</label><br></td>
			</tr>
			<tr>
				<td>Change Security Question:</td>
				<td><input type="radio" name="question" onClick="appear();" value="1" id="yes"><label for="yes">Yes</label></td>
				<td><input type="radio" name="question" onClick="disappear();" value="0" id="no" CHECKED><label for="no">No</label></td>
			</tr>
		</table>
		</div>
		<div id="dynamic"></div>
		</form>		
	</div>
</body>
</html>
