<?php 
session_start();
if($_SESSION['log_status']==1){//already logged in
	echo "<meta http-equiv='refresh' content='0; URL=home.php'>";
	exit(1);
	}
?>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" href="data/css/not_logged_in.css">
	<script language="javascript" src="data/scripts/capcha.js">
	</script>
</head>
<body>
<?php if (isset($_GET['msg'])) { echo "<font color=\"black\"><h3 align=\"center\">".strip_tags($_GET[msg])." </h3></font>"; }?>
<table>
<form action="cuser.php" method="post" align="left">
	<tr>
		<td>Enter NAME:</td>
		<td><input type="text" name="name" size="45"></td>
	</tr>
	<tr>
		<td>Enter username desired:</td>
		<td><input type="text" name="uname" size="45"></td>
	</tr>
	<tr>
		<td>Enter Valid email id:</td>
		<td><input type="text" name="email" size="45"></td>
	</tr>
	<tr>
		<td><img src="pngimg.php" align="middle" type="image/x-png" name="capcha"><br><a href="javascript:new_capcha();" style="font-size:10px;">new image</a></td>
		<td><input type="text" name="code" size="45"></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="send" value="Register"></td>
	</tr>
</form>
</table>
<p>Already have an account?<br><a href=enter.php>LOG IN</a></p>
</body>
</html>